<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\models
 * @category   CategoryName
 */

namespace open20\amos\events\models;

use open20\amos\events\AmosEvents;
use yii\helpers\ArrayHelper;

/**
 * Class EventType
 * This is the model class for table "event_type".
 * @package open20\amos\events\models
 */
class EventType extends \open20\amos\events\models\base\EventType
{
    const TYPE_INFORMATIVE = 100; // Evento informativo
    const TYPE_OPEN = 101; // Evento con iscrizioni aperto
    const TYPE_UPON_INVITATION = 102; // Evento con iscrizioni su invito
    const TYPE_LIMITED_SEATS = 103; // Evento con posti limitati

    const ENABLED = 1;
    const DISABLED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['event_type'], 'required'],
            ['patronage', 'safe'],
            [['limited_seats', 'manage_subscritions_queue', 'partners'], 'integer'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'event_type' => AmosEvents::txt('#event_type'),
            'limited_seats' => AmosEvents::txt('#event_type_limited_seats'),
            'manage_subscritions_queue' => AmosEvents::txt('#event_type_manage_subscritions_queue'),
            'partners' => AmosEvents::txt('#event_type_partners'),
        ]);
    }

    /**
     * Gets options for radio buttons
     * @return array
     */
    public static function getTypeOptions()
    {
        return [
            self::TYPE_INFORMATIVE => AmosEvents::txt('#event_type_informative'),
            self::TYPE_OPEN => AmosEvents::txt('#event_type_open'),
            self::TYPE_UPON_INVITATION => AmosEvents::txt('#event_type_upon_invitation'),
        ];
    }
}
