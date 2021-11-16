<?php

namespace open20\amos\events\models;

use open20\amos\events\AmosEvents;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_internal_list".
 */
class EventInternalList extends \open20\amos\events\models\base\EventInternalList
{

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
            ['template', 'safe']
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
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
            [
                'slug' => 'query_sql',
                'label' => $labels['query_sql'],
                'type' => 'text'
            ],
            [
                'slug' => 'search_params',
                'label' => $labels['search_params'],
                'type' => 'text'
            ],
            [
                'slug' => 'n_user_found',
                'label' => $labels['n_user_found'],
                'type' => 'integer'
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
     * @return int|string
     */
    public function getNSent($statuses)
    {
        if ($this->status == \open20\amos\events\models\EventInternalList::STATUS_SENDING) {
            if (!empty($statuses[$this->id])) {
                if ($statuses[$this->id]['status'] != 'waiting') {
                    if (!empty($statuses[$this->id]['n_sent'])) {
                        return $statuses[$this->id]['n_sent'];
                    }
                }
            }
        }
        if ($this->status == \open20\amos\events\models\EventInternalList::STATUS_SENT) {
          return $this->n_sent;
        }

        return 0;
    }

    /**
     * @return int|string
     */
    public function getTemplate()
    {
        $sent = $this->getEventInvitationSent()->one();
        if ($sent) {
            return $sent->template;
        }
        return '';
    }


    public static function getStatusSending($internaListsIds)
    {
        $statuses = [];

        $newsletterModule = \Yii::$app->getModule('newsletter');
        if ($newsletterModule) {
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $internalLists = EventInternalList::find()->andWhere(['id' => $internaListsIds])->all();
            $sendingIdsLists = [];
            $sendingIds = [];
            foreach ($internalLists as $list) {
                $sendingIdsLists[$list->mailup_sending_id] = $list->id;
                $sendingIds[] = $list->mailup_sending_id;
            }

            $reportSendingOngoing = $mailService->getOngoingSending();
//            pr($reportSendingOngoing, 'ongoing');
            if (!empty($reportSendingOngoing)) {
                foreach ($reportSendingOngoing->Items as $item) {
                    if (!empty($item->Id)) {
                        if (in_array($item->Id, $sendingIds)) {
                            $decodedHistory = $mailService->getEmailSendHistory($item->IdList, $item->IdMessage, []);
//pr($decodedHistory,'history');
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
                    $percent = round(((int)$statuses[$this->id]['n_sent'] / $this->n_user_found) * 100);
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
