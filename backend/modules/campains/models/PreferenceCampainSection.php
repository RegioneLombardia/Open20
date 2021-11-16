<?php

namespace backend\modules\campains\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\Query;

/**
 * This is the model class for table "preference_campain_section".
 */
class PreferenceCampainSection extends \backend\modules\campains\models\base\PreferenceCampainSection {

    /**
     * Metodo beforeSave per il set del ordine della sezione alla creazione del PreferenceCampainSection
     *
     * @param boolean $insert
     * @return void
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // controllo che il model / record sia nuovo, solo alla creazione faccio il set del order iniziale e non all'update
        if( $this->isNewRecord ){

            // estrazione dell'ultimo massimo order
            $query = new Query;
            $max = $query->select('order')
                        ->from('preference_campain_section')
                        ->where(['preference_campain_channel_mm_id' => $this->preference_campain_channel_mm_id])
                        ->orderBy('order DESC')->limit(1)->one();

            // set del nuovo valore dell'order
            $this->order = $max['order'] + 1;
        }

        return true;
    }


    public function representingColumn() {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints() {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute) {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels() {
        return
                ArrayHelper::merge(
                        parent::attributeLabels(),
                        [
        ]);
    }

    public static function getEditFields() {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'color',
                'label' => $labels['color'],
                'type' => 'string'
            ],
            [
                'slug' => 'preference_campain_channel_mm_id',
                'label' => $labels['preference_campain_channel_mm_id'],
                'type' => 'integer'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker() {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents() {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent() {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event 
     */
    public function getColorEvent() {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent() {
        return NULL; //TODO
    }

}
