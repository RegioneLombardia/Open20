<?php

namespace open20\amos\admin\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_profile_external_facilitator".
 */
class UserProfileExternalFacilitator extends \open20\amos\admin\models\base\UserProfileExternalFacilitator
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
                'slug' => 'user_profile_id',
                'label' => $labels['user_profile_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'external_facilitator_id',
                'label' => $labels['external_facilitator_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
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
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchRequestPending($params = []){
        $profile = UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
        $query = UserProfileExternalFacilitator::find()
            ->andWhere(['status' => UserProfileExternalFacilitator::EXTERNAL_FACILITATOR_REQUEST])
            ->andWhere(['external_facilitator_id' => $profile->id]);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        return $dataProvider;
    }


}
