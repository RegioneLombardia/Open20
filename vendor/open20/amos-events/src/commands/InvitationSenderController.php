<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 29/04/2020
 * Time: 11:52
 */

namespace open20\amos\events\commands;


use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\user\User;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventEmailTemplates;
use open20\amos\events\models\EventInternalList;
use open20\amos\events\models\EventInvitationSent;
use open20\amos\events\models\EventPushNotification;
use open20\amos\events\models\EventPushNotificationSent;
use open20\amos\events\utility\EventMailUtility;
use open20\amos\events\utility\EventsUtility;
use open20\amos\notificationmanager\models\NotificationConf;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\db\ActiveQuery;
use yii\helpers\Console;
use yii\helpers\Url;

class InvitationSenderController extends Controller
{
    public $mailService;
    public $mailupListId = 2;
    public $originalMessageId = 2;
    public $dynamics_fields = [
        1 => 'nome',
        2 => 'cognome'
    ];

    /**
     *
     */
    public function init()
    {
        parent::init();

        $moduleEvents = \Yii::$app->getModule('events');
        if ($moduleEvents) {
            $this->mailupListId = $moduleEvents->mailup_configurations['mailup_list_id'];
            $this->originalMessageId = $moduleEvents->mailup_configurations['original_message_id'];
            $this->dynamics_fields = $moduleEvents->mailup_configurations['dynamics_fields'];
        } else {
            throw new InvalidConfigException('the module events must be enabled in common');
        }

        $newsletterModule = \Yii::$app->getModule('newsletter');
        if ($newsletterModule) {
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $this->mailService = new $mailServiceClassname();
        } else {
            throw new InvalidConfigException('the module newsletter must be enabled in common');
        }

    }

    /**
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionImportAndSend()
    {
        Console::stdout('##################################################' . PHP_EOL);
        Console::stdout('##### START CRON import-user-and-send-emails #####' . PHP_EOL);
        Console::stdout('##### ' . date('d-m-Y h:i:s') . '                    #####' . PHP_EOL);
        Console::stdout('##################################################' . PHP_EOL);

        $internalLists = EventInternalList::find()
            ->andWhere(['!=', 'status', EventInternalList::STATUS_SENT])
            ->andWhere(['IS NOT', 'status', null])
            ->all();

//        $q = EventInternalList::find()
//            ->andWhere(['!=', 'status', EventInternalList::STATUS_SENT])
//            ->andWhere(['IS NOT', 'status', null])->createCommand()->rawSql;
//        Console::stdout($q .PHP_EOL);


        /** @var  $internalList EventInternalList */
        foreach ($internalLists as $internalList) {
            try {
                Console::stdout('----------- START LIST ' . $internalList->id . ' - ' . $internalList->name . '----------- ' . PHP_EOL);

                // --- CREATE MALUP GROUP AND  GET USER AND IMPORT TO GROUP
                if ($internalList->status == EventInternalList::STATUS_USERS_TO_IMPORT) {
                    $this->createMailGroup($internalList);
                    $this->getUserAndImport($internalList);
                }

                // ---- SET LIST AS IMPORT COMPLETED
                $importDecoded = $this->mailService->getImport($internalList->mailup_import_id);
                if ($internalList->mailup_import_id) {
                    if ($importDecoded->Completed == true && $internalList->status == EventInternalList::STATUS_IMPORTING_USERS) {
                        Console::stdout('--- USERS IMPORTED' . PHP_EOL);
                        $internalList->status = EventInternalList::STATUS_IMPORTED;
                        $internalList->save(false);
                    }
                }

                // ---- SEND INVITATION
                if ($internalList->status == EventInternalList::STATUS_IMPORTED) {
                    $this->sendInvitation($internalList);
                }

                // ---- SET SENDING TO COMPLETED
                if ($internalList->status == EventInternalList::STATUS_SENDING) {
                    $this->sendingCompleted($internalList);
                }


            } catch (Exception $e) {
                Console::stdout('----------- ERROR !!!! ' . $e->getMessage() . '----------- ' . PHP_EOL);
                Console::stdout('----------- ERROR !!!! ' . $e->getTraceAsString() . '----------- ' . PHP_EOL);
                Console::stdout('----------- END LIST ' . $internalList->id . ' - ' . $internalList->name . '----------- ' . PHP_EOL);
            }
        }

    }


    /**
     * @param $internalList EventInternalList
     */
    public function sendInvitation($internalList)
    {

        //----- GET TEMPLATE TO SEND
        $event = $internalList->event;
        $eventTemplates = $event->eventEmailTemplates;
        $currentTemplate = $internalList->template;

        $attrSubject = $currentTemplate . '_subject';
        $template_attr = $currentTemplate;
        $text = $eventTemplates->$template_attr;
        $footerType = $eventTemplates->getFooterText()[$template_attr];
        Console::stdout('--- template ' . $template_attr . PHP_EOL);


        if ($template_attr == 'registration_email') {
//            $urlYes = \Yii::$app->params['platform']['backendUrl']. Url::toRoute(['/events/event/event-signup', 'eid' => $event->id, 'pName' => "[nome]", 'pSurname' => "[cognome]", 'pEmail' => "[email]"]);
            $urlYes = EventsUtility::getUrlLanding($event) . '?pName=[nome]&pSurname=[cognome]&pEmail=[email]';
            $urlYes = 'http://[track]/' . $urlYes;
//            $urlYes = str_replace('?', ':rQS$:', $urlYes);
//            $urlYes = str_replace('&', ':rQS*:', $urlYes);
            $text .= "<br><a href=\"$urlYes\">Si, parteciperò</a>";

        }

        $logo = $event->eventLogo;
        $urlImage = \Yii::$app->params['platform']['backendUrl'] . "/img/img_default.jpg";
        if ($logo) {
            $urlImage = \Yii::$app->params['platform']['backendUrl'] . $logo->getWebUrl();
        }
        $subject = $eventTemplates->$attrSubject;
        $subjectWithParams = EventMailUtility::parseEmailWithParams($event, null, $subject, false);
        $textWithParms = EventMailUtility::parseEmailWithParams($event, null, $text);

        $newsletterModule = \Yii::$app->getModule('newsletter');
        if ($newsletterModule) {
            // GET MAILUP TEMPLATE AND SUBTITUTION OF [CONTENT] WITH PLATFORM PERSONALIZED EMAIL TEXT
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $emailDecoded = $mailService->getEmail($this->mailupListId, $this->originalMessageId);
            $htmlWithParams = EventMailUtility::parseDynamicContentMailup($urlImage, $textWithParms, $emailDecoded, $footerType);


            // COPY THE MAILUP MESSAGE
            Console::stdout('--- COPY MESSAGE ' . $this->originalMessageId . PHP_EOL);
            $decodedCopiedMessage = $mailService->copyMessage($this->mailupListId, $this->originalMessageId, [
                'Subject' => $subjectWithParams,
                'Notes' => "I_ID: " . $internalList->id . ' E_ID: ' . $event->id
            ]);

            // UPDATE THE MESSAGE COPIED WITH THE MODIFIED TEMPLATE
            $idList = $decodedCopiedMessage->idList;
            $idMessage = $decodedCopiedMessage->idMessage;
            $decoded = $mailService->getEmail($idList, $idMessage);

            $body = [
                'Subject' => $decoded->Subject,
                'idList' => $decoded->idList,
                'Content' => $htmlWithParams,
                'Tags' => $decoded->Tags,
                'TrackingInfo' => $decoded->TrackingInfo,
                'Embed' => $decoded->Embed,
                'IsConfirmation' => $decoded->IsConfirmation,
                'UseDynamicField' => $decoded->UseDynamicField,
                'Structure' => $decoded->Structure
            ];

            Console::stdout('--- UPDATE COPIED MESSAGE ' . $idMessage . PHP_EOL);
            $decodedReponse = $mailService->updateEmail($idList, $idMessage, $body);
            $mailService->enableDisableDynamicFieldsEmail($idList, $idMessage, 'true');

            // SEND MESSAGE TO THE GROUP CRATED FOR THE INVITATION
            Console::stdout('--- SEND EMAIL ' . $internalList->template . ' TO GROUP:  ' . $internalList->mailup_group_id . PHP_EOL);
            $sent = $mailService->sendEmailToGroup($internalList->mailup_group_id, $idMessage);
            $internalList->mailup_message_id = $idMessage;
            $internalList->mailup_list_id = $idList;
            $internalList->mailup_sending_id = $sent->Id;
            $internalList->status = EventInternalList::STATUS_SENDING;
            $internalList->save(false);

        }
    }

    /**
     * @param $internalList EventInternalList
     */
    public function createMailGroup($internalList)
    {

        //-- CREATE THE MAILUP GROUP IF NOT EXISTS
        if (empty($internalList->mailup_group_id)) {
            $event = $internalList->event;
            $parsedTitle = str_replace('\'', '', $event->title);
            $data = [
                'Name' => 'E' . $event->id . '_I' . $internalList->id . '_' . rand(1, 9999) . '_' . substr($parsedTitle, 0, 20),
                'Notes' => 'Event: ' . $event->id . ' Id Internal list: ' . $internalList->id

            ];
            $groupDecoded = $this->mailService->createGroup($this->mailupListId, $data);

            if ($groupDecoded) {
                if (!empty($groupDecoded->ErrorCode)) {
                    Console::stdout(json_encode($groupDecoded) . PHP_EOL);
                } else {
                    Console::stdout('--- CREATE GROUP ' . $groupDecoded->idGroup . PHP_EOL);
                    $internalList->mailup_group_id = $groupDecoded->idGroup;
                    $internalList->mailup_list_id = $this->mailupListId;
                    $internalList->save(false);
                }
            }
        }
    }

    /**
     * @param $internalList EventInternalList
     * @throws \yii\db\Exception
     */
    public function getUserAndImport($internalList)
    {
        if ($internalList->mailup_group_id) {
            //--- GET USERS TO IMPORT TO THE MAILUP GROUP ---
            $query = $internalList->query_sql;
            $result = \Yii::$app->db->createCommand($query)->queryAll();
            $internalList->n_user_found = count($result);
            $internalList->save(false);

            $users = [];
            $i = 0;
            if ($internalList->n_user_found > 0) {
                Console::stdout('--- SAVE INVITATION SENT' . PHP_EOL);
                foreach ($result as $row) {
                    $fields = [];
                    foreach ($this->dynamics_fields as $id => $field) {
                        if (isset($row[$field])) {
                            $fields[] = ['Id' => $id, 'Value' => $row[$field]];
                        }
                    }
                    $users [] = [
                        'Email' => $row['email'],
                        'Fields' => $fields,
                        'Name' => ''
                    ];

                    //--- SAVE THE INVITATION WHICH YOU ARE SENDING --------
                    if ($internalList->template) {
                        $i++;
                        $intivationSent = new EventInvitationSent();
                        $intivationSent->event_internal_list_id = $internalList->id;
                        $intivationSent->user_id = $row['user_id'];
                        $intivationSent->email = $row['email'];
                        $intivationSent->send_at = date('Y-m-d H:i:s');
                        $intivationSent->template = $internalList->template;
                        $intivationSent->save();

                        if ($intivationSent) {
                            \open20\amos\core\models\UserActivityLog::registerLog(AmosEvents::t('amosevents', 'Invio email di invito'), $intivationSent, Event::LOG_TYPE_INVITATION_SENT, null, $intivationSent->user_id);
                        }
                    }
                }

                // --- IMPORT USERS TO THE GROUP AND UPDATE DATAS
                Console::stdout('--- IMPORT ' . $internalList->n_user_found . ' USERS to group ' . $internalList->mailup_group_id . PHP_EOL);
                $importId = $this->mailService->importRecipientsToGroups($users, $internalList->mailup_group_id);
                $internalList->mailup_import_id = $importId;
                $internalList->status = EventInternalList::STATUS_IMPORTING_USERS;
                $internalList->save(false);
            }
        } else {
            Console::stdout('--- NO USER TO IMPORT ' . PHP_EOL);
            $internalList->status = EventInternalList::STATUS_NO_USER_TO_IMPORT;
            $internalList->save(false);
        }
    }

    /**
     * @param $internalList
     */
    public function sendingCompleted($internalList)
    {
        $isComplete = true;
        $reportSendingWiting = $this->mailService->getWaitingSending();
        if (!empty($reportSendingWiting)) {
            foreach ($reportSendingWiting->Items as $item) {
                if (!empty($item->Id)) {
                    $isComplete = false;
                }

            }
        }
        $reportSendingOngoing = $this->mailService->getOngoingSending();
        if (!empty($reportSendingOngoing)) {
            foreach ($reportSendingOngoing->Items as $item) {
                if (!empty($item->Id)) {
                    $isComplete = false;
                }

            }
        }
        if ($isComplete) {
            Console::stdout('--- SENDING COMPLETE' . PHP_EOL);
            $internalList->status = EventInternalList::STATUS_SENT;
            $history = $this->mailService->getEmailSendHistory($internalList->mailup_list_id, $internalList->mailup_message_id, []);
            $n = 0;

            if (!empty($history->Items)) {
                $elem = $history->Items[0];
                $n = $elem->Recipients;
            }
            $internalList->n_sent = $n;
            $internalList->save(false);
        }
    }


    /**
     * @throws InvalidConfigException
     */
    public function actionSendPushNotification()
    {
        Console::stdout('##################################################' . PHP_EOL);
        Console::stdout('##### START CRON send-push-notification      #####' . PHP_EOL);
        Console::stdout('##### ' . date('d-m-Y h:i:s') . '                    #####' . PHP_EOL);
        Console::stdout('##################################################' . PHP_EOL);

        $pushes = EventPushNotification::find()->andWhere(['status' => EventPushNotification::STATUS_DRAFT])->all();
        /** @var  $push EventPushNotification */
        foreach ($pushes as $push) {
            try {
                $i = 0;
                $event = Event::findOne($push->event_id);
                Console::stdout('---- START PUSH ' . $push->id . ' event_id:' . $event->id . PHP_EOL);
                Console::stdout('---- TYPE ' . $push->type . PHP_EOL);

                $class = $push->content_class;
                $id = $push->content_id;
                $title = '';
                $text = '';
                $object = $class::findOne($id);
                $modelClassname = ModelsClassname::find()->andWhere(['classname' => $class])->one();

                // INVITATION / SAVE THE DATE
                if ($push->type == EventPushNotification::TYPE_SAVE_THE_DATE || $push->type == EventPushNotification::TYPE_INVITE_REGISTER) {
                    if ($push->type == EventPushNotification::TYPE_SAVE_THE_DATE) {
                        $title = AmosEvents::t('amosevents', "Save the date");
                        $text = AmosEvents::t('amosevents', "{title}, è in arrivo un fantastico evento!", [
                            'title' => $event->title
                        ]);
                    } else if ($push->type == EventPushNotification::TYPE_INVITE_REGISTER) {
                        $title = AmosEvents::t('amosevents', "Invito alla registrazione");
                        $text = AmosEvents::t('amosevents', "Ti invitiamo a iscriverti all’evento {title}", [
                            'title' => $event->title
                        ]);
                    }

                    $activeQuery = unserialize(urldecode($object->active_query));
                    $activeQuery->select('user.id');
//                pr($query->createCommand()->rawSql);

                    // PUBLICATION CONTENTS (NEWS / DISCUSSIONI / DOCUMENTI /SONDAGGI )
                } else if ($push->type == EventPushNotification::TYPE_NEW_CONTENT) {
                    $community = $event->community;
                    if ($community) {
                        $grammar = $object->getGrammar();
                        $title = AmosEvents::t('amosevents', "Pubblicazione di")
                            . ' '
                            . $grammar->getIndefiniteArticle()
                            . ' '
                            . $grammar->getModelSingularLabel();

                        $text = AmosEvents::t('amosevents', "Dai un'occhiata a {title}", [
                            'title' => $object->getTitle()
                        ]);
                        $activeQuery = CommunityUserMm::find()
                            ->select('community_user_mm.user_id')
                            ->andWhere(['community_id' => $community->id])
                            ->andWhere(['status' => CommunityUserMm::STATUS_ACTIVE]);
                    }
                }

                // QUERY GET USER TO NOTIFY
            $query = User::find()
                ->distinct()
                ->innerJoin('access_tokens', 'user.id = access_tokens.user_id')
                ->andWhere(['!=', 'fcm_token', ''])
                ->andWhere(['OR', ['!=', 'device_os', 'cms'], ['device_os' => null]])
                ->andWhere(['access_tokens.user_id' => $activeQuery]);

//                $query = \open20\amos\mobile\bridge\modules\v1\models\base\AccessTokens::find()
//                    ->innerJoin('user', 'user.id = access_tokens.user_id')
//                    ->andWhere(['!=', 'fcm_token', ''])
//                    ->andWhere(['OR', ['!=', 'device_os', 'cms'], ['device_os' => null]])
//                    ->andWhere(['access_tokens.user_id' => $activeQuery]);

                // CHECK USER NOTIFY CONFIGURATIONS
                if ($modelClassname) {
                    $userToNotNotifyQuery = NotificationConf::find()
                        ->distinct()
                        ->select('notificationconf.user_id')
                        ->innerJoinWith('notificationConfContents')
                        ->andWhere(['OR',
                            ['push_notification' => 0],
                            ['notifications_enabled' => 0],
                        ])
                        ->andWhere(['models_classname_id' => $modelClassname->id]);
                    $query->andWhere(['NOT IN', 'user.id', $userToNotNotifyQuery]);
                }
                //                pr($query->createCommand()->rawSql);
                $result = $query->asArray()->all();

                foreach ($result as $user) {
                    $ok = \open20\amos\mobile\bridge\utility\NotificationPushUtility::sendNotification($user['id'], $title, $text, 'event', $event->id);
                    if ($ok) {
                        // Console::stdout('token '. $token['user_id']. PHP_EOL);

                        $i++;
                        $sent = new EventPushNotificationSent();
                        $sent->user_id = $user['id'];
                        $sent->event_push_notification_id = $push->id;
                        $sent->sent_at = date('Y-m-d H:i:s');
                        $sent->status = 'sent';
                        $ok = $sent->save(false);

                    }
                }
                $push->status = EventPushNotification::STATUS_SENT;
                $push->save(false);
                Console::stdout('--- n. SENT  ' . $i . PHP_EOL);

            } catch (\yii\console\Exception $e) {
                Console::stdout('--- ERR ' . $e->getMessage() . PHP_EOL);
                Console::stdout('--- ERR ' . $e->getFile() . ' - ' . $e->getLine() . PHP_EOL);
                Console::stdout('--- ERR ' . $e->getTraceAsString() . PHP_EOL);
            }
        }
    }


}