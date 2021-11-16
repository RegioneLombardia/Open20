<?php

namespace open20\amos\events\models;

use open20\amos\attachments\behaviors\FileBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_container".
 */
class EventContainer extends \open20\amos\events\models\base\EventContainer
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fileBehavior' => [
                    'class' => FileBehavior::className()
                ],
            ]
        );
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
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'string'
            ],
            [
                'slug' => 'luya_template_id',
                'label' => $labels['luya_template_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'luya_page_id',
                'label' => $labels['luya_page_id'],
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
     *
     */
    public function loadEventsConnected(){
        $this->eventsConnected = $this->events;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function saveEventConnected(){
        if($this->eventsConnected){
            $eventsToDelete = Event::find()->andWhere(['event_container_id' => $this->id])->all();
            foreach ($eventsToDelete as $eventToDel) {
                $eventToDel->event_container_id = null;
                $eventToDel->save(false);
            }
            $i = 1;
            foreach ($this->eventsConnected as $idEvent){
                $event = Event::findOne($idEvent);
                $event->event_container_id = $this->id;
                $event->event_container_order = $i++;
                $event->save(false);
                $i++;
            }
        }
    }
}
