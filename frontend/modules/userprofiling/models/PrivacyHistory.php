<?php

namespace frontend\modules\userprofiling\models;

use open20\amos\core\user\User;
use frontend\models\color;
use frontend\models\title;
use frontend\models\url;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "privacy_history".
 */
class PrivacyHistory extends base\PrivacyHistory{
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


    public static function getEditFields() {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'id_user',
                'label' => $labels['id_user'],
                'type' => 'integer'
            ],
            [
                'slug' => 'timestamp',
                'label' => $labels['timestamp'],
                'type' => 'integer'
            ],
            [
                'slug' => 'isAccepted',
                'label' => $labels['isAccepted'],
                'type' => 'tinyint'
            ],
        ];
    }

    public function beforeSave($insert)
    {
        return true;
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

    /** Funzione che ritorna i periodi dopo i quali inviare notifiche per data retention
     * @return array[]
     */
    public static function getRetentionDefault() {
        // [anni, mesi, settimane]
        return [
            [4, 11, 3],
            [0, 0, 1]
        ];
    }

    /** Funzione che ritorna i diversi periodi che indicano quando inviare notifiche di data retention
     * @return array[]
     */
    public static function getRetentionLevels() {
        $periodi = self::getRetentionDefault();
        $result = (isset(Yii::$app->params['retention-levels'])) ? Yii::$app->params['retention-levels'] : $periodi;
        // [anni, mesi, settimane]
        return $result;
    }

    public static function getRetentionUsers() {
        $subquery = PrivacyHistory::find()
            ->select(['id_user', 'MAX(timestamp) as recente'])
            ->andWhere(['>', 'id_user', 3])
            ->groupBy('id_user');

        $result = PrivacyHistory::find()
            ->innerJoin(['sq' => $subquery], 'sq.id_user = privacy_history.id_user AND sq.recente = privacy_history.timestamp')
            ->joinWith('user')
            ->all();
        return $result;
    }

    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }
}