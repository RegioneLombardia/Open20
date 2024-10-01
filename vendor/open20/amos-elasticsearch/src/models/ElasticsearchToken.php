<?php

namespace open20\elasticsearch\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "elasticsearch_token".
 */
class ElasticsearchToken extends \open20\elasticsearch\models\base\ElasticsearchToken {

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

    public function getEditFields() {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'token',
                'label' => $labels['token'],
                'type' => 'string'
            ],
            [
                'slug' => 'time',
                'label' => $labels['time'],
                'type' => 'integer'
            ],
            [
                'slug' => 'consumed',
                'label' => $labels['consumed'],
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

    /**
     * 
     * @param string $token
     * @param string $url
     * @return bool
     */
    public static function checkTokens($token, $url) {
        $validToken = \Yii::$app->db->createCommand("select count(*) from elasticsearch_token where token=:token and url=:url and consumed = 0 and (UNIX_TIMESTAMP() - time) <= 120")
                ->bindValue(':token', $token)
                ->bindValue(':url', $url)
                ->queryScalar();

        return ($validToken > 0);
    }

    /**
     * 
     * @param string $url
     * @return string
     */
    public static function generateTokens($url) {
        $date = new \DateTime();
        $timestamp = $date->getTimestamp();
        $hash = md5($timestamp . $url);
        $token = $hash;
        \Yii::$app->db->createCommand()->insert(self::tableName(),
                [
                    'token' => $token,
                    'url' => $url,
                    'time' => new \yii\db\Expression('UNIX_TIMESTAMP()'),
                    'consumed' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_by' => 1,
                    'updated_by' => 1,
                ])->execute();
        return $token;
    }

    /**
     * 
     * @param string $token
     * @return bool
     */
    public static function consumeTokens($token) {
        \Yii::$app->db->createCommand()->update(self::tableName(),
                [
                    'consumed' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ], ['token' => $token])->execute();
        return true;
    }
}
