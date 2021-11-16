<?php

namespace open20\amos\events\models;

use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\user\User;
use open20\amos\events\AmosEvents;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_communication".
 */
class EventCommunication extends \open20\amos\events\models\base\EventCommunication
{

    const TYPE_INVITED_SENT = 1;
    const TYPE_REGISTERED = 2;
    const TYPE_INVITED_NOT_REGISTED = 3;

    public $email;


    public function init()
    {
        parent::init();
        if($this->isNewRecord){
            $this->text_email = self::defaultTextCommunication();
            $this->subject_email = self::defaultSubjectCommunication();
        }
    }

    /**
     * @return string
     */
    public static function defaultSubjectCommunication(){
        return  AmosEvents::t('amosevents', "{NOME}, il tuo evento ha cambiato data!");
    }

    /**
     * @return string
     */
    public static function defaultTextCommunication(){
        $module = \Yii::$app->getModule('events');
        $linkApp = null;
        if($module){
            $linkApp = $module->url_download_app_mobile;
        }

        return AmosEvents::t('amosevents', "Gentile {NOME} {COGNOME}, abbiamo cambiato la data dell'evento a cui ti sei iscritto.<br>
            L'evento {TITOLO} si terrà il 01 marzo 2021.<br>
            Ora e luogo restano invece invariati: {TITOLO} inizierà il 01 marzo 2021 alle ore {ORA_INIZIO} presso {LOCATION}, {INDIRIZZO}.<br>
            Segna la data in agenda, noi ti aspettiamo!<br><br>
            Non perderti tutti gli aggiornamenti: segui <a href='{URL_LANDING}'>la pagina dell'iniziativa</a> oppure scarica <a href='$linkApp'>l’app Eventi Lombardia</a>!
            <br><br>
            A presto
        ");
    }

    /**
     * @return array
     */
    public static function getCommunicationTypeLabels()
    {
        return [
            self::TYPE_INVITED_SENT => AmosEvents::t('amosevents', 'Tutti gli utenti invitati (a cui è stato spedita una email di tipo save the date o invito'),
            self::TYPE_REGISTERED => AmosEvents::t('amosevents', 'Solo gli utenti registrati (partecipanti)'),
            self::TYPE_INVITED_NOT_REGISTED => AmosEvents::t('amosevents', 'Solo gli utenti invitati che ancora non si sono registrati'),
        ];
    }

    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['email', 'email']
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'event_id',
                'label' => $labels['event_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'communication_type',
                'label' => $labels['communication_type'],
                'type' => 'integer'
            ],
            [
                'slug' => 'n_users',
                'label' => $labels['n_users'],
                'type' => 'integer'
            ],
            [
                'slug' => 'text_email',
                'label' => $labels['text_email'],
                'type' => 'text'
            ],
            [
                'slug' => 'subject_email',
                'label' => $labels['subject_email'],
                'type' => 'text'
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string'
            ],
            [
                'slug' => 'sent_at',
                'label' => $labels['sent_at'],
                'type' => 'datetime'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }


    /**
     * @param $type
     * @param $event_id
     * @return ActiveQuery
     */
    public function searchUsersQuery($event_id)
    {
        /** @var  $query ActiveQuery */
        $query = User::find()
            ->distinct()
            ->innerJoinWith('userProfile')
            ->andWhere(['user_profile.attivo' => 1])
            ->andWhere(['NOT LIKE', 'user_profile.nome', UserProfileUtility::DELETED_ACCOUNT_NAME ]);

        switch ($this->communication_type) {
            case self::TYPE_INVITED_SENT:
                $query->innerJoin('event_invitation_sent', 'event_invitation_sent.user_id = user.id')
                    ->innerJoin('event_internal_list', 'event_invitation_sent.event_internal_list_id = event_internal_list.id')
                    ->andWhere(['event_internal_list.event_id' => $event_id])
                    ->groupBy('user_profile.user_id');

                break;
            case self::TYPE_REGISTERED:
                $query->innerJoin('community_user_mm', 'community_user_mm.user_id = user.id')
                    ->innerJoin('event_invitation', 'event_invitation.user_id = community_user_mm.user_id')
                    ->innerJoin('event', 'event.community_id = community_user_mm.community_id')
                    ->andWhere(['event.id' => $event_id])
                    ->andWhere(['community_user_mm.status' => CommunityUserMm::STATUS_ACTIVE])
                    ->groupBy('user_profile.user_id');
                break;
            case self::TYPE_INVITED_NOT_REGISTED:
                $query2 = clone $query;
                $query2->select('user.id')->innerJoin('community_user_mm', 'community_user_mm.user_id = user.id')
                    ->innerJoin('event', 'event.community_id = community_user_mm.community_id')
                    ->innerJoin('event_invitation', 'event_invitation.user_id = community_user_mm.user_id')
                    ->andWhere(['event.id' => $event_id])
                    ->andWhere(['community_user_mm.status' => CommunityUserMm::STATUS_ACTIVE])
                    ->groupBy('user_profile.user_id');


                $query->innerJoin('event_invitation_sent', 'event_invitation_sent.user_id = user.id')
                    ->innerJoin('event_internal_list', 'event_invitation_sent.event_internal_list_id = event_internal_list.id')
                    ->andWhere(['event_internal_list.event_id' => $event_id])
                    ->andWhere(['not in', 'user.id', $query2])
                    ->groupBy('user_profile.user_id');

                break;
            default:
                $query->andWhere(0);
        }
        return $query;
    }

    /**
     * @param $communicationsIds
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getStatusSending($communicationsIds)
    {
        $statuses = [];

        $newsletterModule = \Yii::$app->getModule('newsletter');
        if ($newsletterModule) {
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $communications = EventCommunication::find()->andWhere(['id' => $communicationsIds])->all();
            $sendingIdsLists = [];
            $sendingIds = [];
            foreach ($communications as $communication) {
                $sendingIdsLists[$communication->mailup_sending_id] = $communication->id;
                $sendingIds[] = $communication->mailup_sending_id;
            }

            $reportSendingOngoing = $mailService->getOngoingSending();
            if (!empty($reportSendingOngoing)) {
                foreach ($reportSendingOngoing->Items as $item) {
                    if (!empty($item->Id)) {
                        if (in_array($item->Id, $sendingIds)) {
                            $decodedHistory = $mailService->getEmailSendHistory($item->IdList, $item->IdMessage, []);
                            $n = 0;
                            if (!empty($decodedHistory->Items)) {
                                $elem = $decodedHistory->Items[0];
                                $n = $elem->Recipients;
                            }
                            $statuses[$sendingIdsLists[$item->Id]] = ['status' => 'running', 'n_sent' => $n];
                        }
                    }

                }
            }

            $reportSendingWiting = $mailService->getWaitingSending();//                        pr($reportSendingWiting, 'waiting');
            ;
            if (!empty($reportSendingWiting)) {
                foreach ($reportSendingWiting->Items as $item) {
                    if (!empty($item->Id)) {
                        if (in_array($item->Id, $sendingIds)) {
                            $statuses[$sendingIdsLists[$item->Id]] = ['status' => 'waiting'];
                        }
                    }

                }
            }
        }

        return $statuses;
    }


    /**
     * @param $statuses
     * @return string
     */
    public function getSingleStatusSending($statuses)
    {
        if ($this->status == \open20\amos\events\models\EventInternalList::STATUS_SENDING) {
            if (!empty($statuses[$this->id])) {
                if ($statuses[$this->id]['status'] == 'waiting') {
                    return AmosEvents::t('amosevents', 'In coda');
                } else {
                    $percent = round(((int)$statuses[$this->id]['n_sent'] / $this->n_users) * 100);
                    return AmosEvents::t('amosevents', "Invio in corso") . ': ' . $percent . '%';
                }
            } else {
                return AmosEvents::t('amosevents', 'Invio completato');
            }
        } else {
            if ($this->status) {
                return self::getLabelStatus()[$this->status];
            }
        }
        return AmosEvents::t('amosevents', "Bozza");
    }


}
