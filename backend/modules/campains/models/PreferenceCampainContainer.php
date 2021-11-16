<?php

namespace backend\modules\campains\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\Query;

/**
 * This is the model class for table "preference_campain_container".
 */
class PreferenceCampainContainer extends \backend\modules\campains\models\base\PreferenceCampainContainer {


    /**
     * Metodo beforeSave per il set del ordine del contenuto alla creazione del PreferenceCampainContainer
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

            if( empty($this->order) ){
                // estrazione dell'ultimo massimo order
                $query = new Query;
                $max = $query->select('order')
                            ->from('preference_campain_container')
                            ->where(['preference_campain_section_id' => $this->preference_campain_section_id])
                            ->orderBy('order DESC')->limit(1)->one();

                // set del nuovo valore dell'order
                $this->order = $max['order'] + 1;
            }
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
            ArrayHelper::merge(parent::attributeLabels(),[]);
    }

    public static function getEditFields() {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'separator_title',
                'label' => $labels['separator_title'],
                'type' => 'string'
            ],
            [
                'slug' => 'content_image_path',
                'label' => $labels['content_image_path'],
                'type' => 'string'
            ],
            [
                'slug' => 'content_align',
                'label' => $labels['content_align'],
                'type' => 'string'
            ],
            [
                'slug' => 'content_highlight_flag',
                'label' => $labels['content_highlight_flag'],
                'type' => 'integer'
            ],
            [
                'slug' => 'content_title',
                'label' => $labels['content_title'],
                'type' => 'string'
            ],
            [
                'slug' => 'content_text',
                'label' => $labels['content_text'],
                'type' => 'text'
            ],
            [
                'slug' => 'content_news_link',
                'label' => $labels['content_news_link'],
                'type' => 'string'
            ],
            [
                'slug' => 'content_facebook_link',
                'label' => $labels['content_facebook_link'],
                'type' => 'string'
            ],
            [
                'slug' => 'content_twitter_link',
                'label' => $labels['content_twitter_link'],
                'type' => 'string'
            ],
            [
                'slug' => 'content_linkedin_link',
                'label' => $labels['content_linkedin_link'],
                'type' => 'string'
            ],
            [
                'slug' => 'preference_campain_section_id',
                'label' => $labels['preference_campain_section_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'preference_campain_container_type_id',
                'label' => $labels['preference_campain_container_type_id'],
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
