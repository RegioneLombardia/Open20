<?php

namespace open20\socialwall\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "socialwall_tokens".
 */
class SocialwallTokens extends \open20\socialwall\models\base\SocialwallTokens {

    public function getTwitter_username() {
        $result = null;

        try {
            $data = Json::decode($this->data);

            if(array_key_exists('username', $data)) {
                $result = $data['username'];
            }
        } catch (\Exception $e) {
            // DO NOTHING (will return null)
        }

       return $result;
    }

    public function setTwitter_username($value) {
        try {
            $data = Json::decode($this->data);

            $data['username'] = $value;

            $this->data = Json::encode($data);
        } catch (\Exception $e) {
            // DO NOTHING
        }
    }

    public function getTwitter_token() {
        $result = null;

        try {
            $data = Json::decode($this->data);

            if(array_key_exists('token', $data)) {
                $result = $data['token'];
            }
        } catch (\Exception $e) {
            // DO NOTHING (will return null)
        }

        return $result;
    }

    public function setTwitter_token($value) {
        try {
            $data = Json::decode($this->data);

            $data['token'] = $value;

            $this->data = Json::encode($data);
        } catch (\Exception $e) {
            // DO NOTHING
        }
    }

    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints(){
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


    public function getEditFields() {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'type',
                'label' => $labels['type'],
                'type' => 'string'
            ],
            [
                'slug' => 'data',
                'label' => $labels['data'],
                'type' => 'text'
            ],
            [
                'slug' => 'secret',
                'label' => $labels['secret'],
                'type' => 'string'
            ],
            [
                'slug' => 'expires_at',
                'label' => $labels['expires_at'],
                'type' => 'datetime'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker(){
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

    public function getTitle() {
        return "Tokens";
    }



}
