<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 29/04/2020
 * Time: 11:52
 */

namespace open20\amos\events\commands;


use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventCommunication;
use open20\amos\events\models\EventCommunicationSent;
use open20\amos\events\models\EventEmailTemplates;
use open20\amos\events\models\EventInvitationSent;
use open20\amos\events\models\EventLanding;
use open20\amos\events\utility\EventMailUtility;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\Console;
use yii\helpers\Url;

class CommunicationSenderController extends Controller
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

    public function actionSendMemoStreaming()
    {
        Console::stdout('##########################################################' . PHP_EOL);
        Console::stdout('##### START CRON send-memo-streaming #####' . PHP_EOL);
        Console::stdout('##### ' . date('d-m-Y h:i:s') . '                    #####' . PHP_EOL);
        Console::stdout('##########################################################' . PHP_EOL);

        $eventLandings = EventLanding::find()
            ->innerJoinWith('event')
            ->andWhere(['is not', 'streaming_url', null])
            ->andWhere(['is', 'event_communication_memo_id', null])
            ->andWhere(['<=', 'event_landing.date_sending_memo', new Expression('NOW()')])->all();

        /** @var  $landing EventLanding */
        foreach ($eventLandings as $landing) {
            Console::stdout('-- START EVENT id:' . $landing->event_id . PHP_EOL);
            try {
                $communication = new EventCommunication();
                $communication->event_id = $landing->event_id;
                $communication->text_email = $landing->text_sending_memo;
                $communication->subject_email = $landing->subject_sending_memo;
                $communication->title = AmosEvents::t('amosevents', "Promemoria streaming");
                $communication->communication_type = EventCommunication::TYPE_REGISTERED;
                $query = $communication->searchUsersQuery($landing->event_id);
                $communication->n_users = $query->count();
                $communication->is_automatic = true;
                $communication->created_by = 1;
                $communication->status = EventCommunication::STATUS_USERS_TO_IMPORT;
                if ($communication->save(false)) {
                    $landing->event_communication_memo_id = $communication->id;
                    $landing->save(false);
                    Console::stdout('- PROCESSING MEMO' . PHP_EOL);
                }
                Console::stdout('--- END id:' . $landing->event_id . PHP_EOL);

            } catch (Exception $e) {
                Console::stdout('--- ERROR --- ' . $e->getMessage() . PHP_EOL);
            }

        }
    }
    //----------------------

    /**
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionImportAndSendCommunications()
    {
        Console::stdout('##########################################################' . PHP_EOL);
        Console::stdout('##### START CRON import-user-and-send-communications #####' . PHP_EOL);
        Console::stdout('##### ' . date('d-m-Y h:i:s') . '                    #####' . PHP_EOL);
        Console::stdout('##########################################################' . PHP_EOL);

        $communications = EventCommunication::find()
            ->andWhere(['!=', 'status', EventCommunication::STATUS_SENT])
            ->andWhere(['IS NOT', 'status', null])
            ->all();

        /** @var  $communication EventCommunication */
        foreach ($communications as $communication) {
            try {
                Console::stdout('----------- START LIST ' . $communication->id . ' - ' . $communication->title . '----------- ' . PHP_EOL);

                // --- CREATE MALUP GROUP AND  GET USER AND IMPORT TO GROUP
                if ($communication->status == EventCommunication::STATUS_USERS_TO_IMPORT) {
                    $this->createMailGroup($communication);
                    $this->getUserAndImport($communication);
                }

                // ---- SET LIST AS IMPORT COMPLETED
                $importDecoded = $this->mailService->getImport($communication->mailup_import_id);
                if ($communication->mailup_import_id) {
                    if ($importDecoded->Completed == true && $communication->status == EventCommunication::STATUS_IMPORTING_USERS) {
                        Console::stdout('--- USERS IMPORTED' . PHP_EOL);
                        $communication->status = EventCommunication::STATUS_IMPORTED;
                        $communication->save(false);
                    }
                }

                // ---- SEND INVITATION
                if ($communication->status == EventCommunication::STATUS_IMPORTED) {
                    $this->sendCommunication($communication);
                }

                // ---- SET SENDING TO COMPLETED
                if ($communication->status == EventCommunication::STATUS_SENDING) {
                    $this->sendingCompleted($communication);
                }

                Console::stdout('----------- END LIST ' . $communication->id . ' - ' . $communication->title . '----------- ' . PHP_EOL);

            } catch (Exception $e) {
                Console::stdout(' --- ERROR --- ' . $e->getMessage() . PHP_EOL);
            }
        }

    }


    /**
     * @param $communication EventCommunication
     */
    public function sendCommunication($communication)
    {

        //----- GET TEMPLATE TO SEND
        $event = $communication->event;

        $text = $communication->text_email;
        $subject = $communication->subject_email;

        $subjectWithParams = EventMailUtility::parseEmailWithParams($event, null, $subject, false);
        $textWithParms = EventMailUtility::parseEmailWithParams($event, null, $text);

        $logo = $event->eventLogo;
        $urlImage = \Yii::$app->params['platform']['backendUrl'] . "/img/img_default.jpg";
        if ($logo) {
            $urlImage = \Yii::$app->params['platform']['backendUrl'] . $logo->getWebUrl();
        }
        $newsletterModule = \Yii::$app->getModule('newsletter');
        if ($newsletterModule) {
            // GET MAILUP TEMPLATE AND SUBTITUTION OF [CONTENT] WITH PLATFORM PERSONALIZED EMAIL TEXT
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $emailDecoded = $mailService->getEmail($this->mailupListId, $this->originalMessageId);
            $htmlWithParams = EventMailUtility::parseDynamicContentMailup($urlImage, $textWithParms, $emailDecoded, EventEmailTemplates::FOOTER_TYPE_TAG_AND_UNSUBSCRIBE);


            // COPY THE MAILUP MESSAGE
            Console::stdout('--- COPY MESSAGE ' . $this->originalMessageId . PHP_EOL);
            $decodedCopiedMessage = $mailService->copyMessage($this->mailupListId, $this->originalMessageId, [
                'Subject' => $subjectWithParams,
                'Notes' => "C_ID: " . $communication->id . ' E_ID: ' . $event->id
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

            // SEND MESSAGE TO THE GROUP CRATED FOR THE COMMUNCATION
            Console::stdout('--- SEND EMAIL ' . $communication->title . ' TO GROUP:  ' . $communication->mailup_group_id . PHP_EOL);
            $sent = $mailService->sendEmailToGroup($communication->mailup_group_id, $idMessage);
            $communication->mailup_message_id = $idMessage;
            $communication->mailup_list_id = $idList;
            $communication->mailup_sending_id = $sent->Id;
            $communication->status = EventCommunication::STATUS_SENDING;
            $communication->save(false);

        }
    }

    /**
     * @param $communication EventCommunication
     */
    public function createMailGroup($communication)
    {
        //-- CREATE THE MAILUP GROUP IF NOT EXISTS
        if (empty($communication->mailup_group_id)) {
            $event = $communication->event;
            $parsedTitle = str_replace('\'', '', $communication->title);

            $data = [
                'Name' => 'E' . $event->id . '_C' . $communication->id . '_' . rand(1,9999).'_'.substr($parsedTitle, 0, 20),
                'Notes' => 'Communication Event: ' . $event->id . ' Id Communication: ' . $communication->id
            ];
            $groupDecoded = $this->mailService->createGroup($this->mailupListId, $data);
            if (!empty($groupDecoded->ErrorCode)) {
                Console::stdout(json_encode($groupDecoded) . PHP_EOL);
            } else if (!empty($groupDecoded->idGroup) && $groupDecoded) {
                Console::stdout('--- CREATE GROUP ' . $groupDecoded->idGroup . PHP_EOL);
                $communication->mailup_group_id = $groupDecoded->idGroup;
                $communication->mailup_list_id = $this->mailupListId;
                $communication->save(false);
            }
        }
    }

    /**
     * @param $communication EventCommunication
     * @throws \yii\db\Exception
     */
    public function getUserAndImport($communication)
    {
        if ($communication->mailup_group_id) {
            //--- GET USERS TO IMPORT TO THE MAILUP GROUP ---
            /** @var  $query ActiveQuery */
            $query = $communication->searchUsersQuery($communication->event_id);
            $query2 = $query
                ->select(new Expression("user.*, user_profile.*, istat_comuni.nome as 'comune', istat_province.nome as 'provincia', user_profile_age_group.age_group as eta"))
                ->leftJoin('user_profile_age_group', 'user_profile_age_group.id = user_profile.user_profile_age_group_id')
                ->leftJoin('istat_comuni', 'istat_comuni.id = user_profile.nascita_comuni_id')
                ->leftJoin('istat_province', 'istat_province.id = user_profile.nascita_province_id');
            $result = $query2->asArray()->all();
            $communication->n_users = count($result);
            $communication->save(false);

            $users = [];
            $i = 0;
            if ($communication->n_users > 0) {
                Console::stdout('--- SAVE COMMUNCATIONS  SENT' . PHP_EOL);
                foreach ($result as $row) {
                    $fields = [];
                    foreach ($this->dynamics_fields as $id => $field) {
                        if(isset($row[$field])) {
                            $fields[] = ['Id' => $id, 'Value' => $row[$field]];
                        }
                    }
                    $users [] = [
                        'Email' => $row['email'],
                        'Fields' => $fields,
                        'Name' => ''
                    ];

                    //--- SAVE THE INVITATION WHICH YOU ARE SENDING --------
                    $i++;
                    $communicationSent = new EventCommunicationSent();
                    $communicationSent->event_communication_id = $communication->id;
                    $communicationSent->user_id = $row['user_id'];
                    $communicationSent->email = $row['email'];
                    $communicationSent->sent_at = date('Y-m-d H:i:s');
                    $communicationSent->save();
                    if ($communicationSent) {
                        \open20\amos\core\models\UserActivityLog::registerLog(AmosEvents::t('amosevents', 'Invio comunicazione'), $communicationSent, Event::LOG_TYPE_COMMUNICATION_SENT, null, $communicationSent->user_id);
                    }
                }

                // --- IMPORT USERS TO THE GROUP AND UPDATE DATAS
                Console::stdout('--- IMPORT ' . $communication->n_users . ' USERS to group ' . $communication->mailup_group_id . PHP_EOL);
                $importId = $this->mailService->importRecipientsToGroups($users, $communication->mailup_group_id);
                $communication->mailup_import_id = $importId;
                $communication->status = EventCommunication::STATUS_IMPORTING_USERS;
                $communication->save(false);
            } else {
                Console::stdout('--- NO USER TO IMPORT '. PHP_EOL);
                $communication->status = EventCommunication::STATUS_NO_USER_TO_IMPORT;
                $communication->save(false);
            }
        }
    }

    /**
     * @param $communication
     */
    public function sendingCompleted($communication)
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
            $communication->status = EventCommunication::STATUS_SENT;
            $history = $this->mailService->getEmailSendHistory($communication->mailup_list_id, $communication->mailup_message_id, []);
            $n = 0;
            if (!empty($history->Items)) {
                $elem = $history->Items[0];
                $n = $elem->Recipients;
            }
            $communication->n_sent = $n;
            $communication->save(false);
        }
    }


}