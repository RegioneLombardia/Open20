<?php

namespace backend\modules\landings\models\base;

use Yii;

/**
 * This is the base-model class for table "preference_landing_rating".
 *
 * @property integer $id
 * @property integer $preference_landing_id
 * @property integer $user_id
 * @property integer $rating
 * @property string $ip
 * @property string $maturity
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class PreferenceLandingRating extends \open20\amos\core\record\Record
{

    const FRONTEND_RATING_COOKIE_NAME = 'RATING_L';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preference_landing_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['preference_landing_id', 'user_id', 'rating', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['ip'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['maturity'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('landing', 'ID'),
            'territory_id' => Yii::t('landing', 'Territory'),
            'user_id' => Yii::t('landing', 'Utente'),
            'rating' => Yii::t('landing', 'Rating'),
            'ip' => Yii::t('landing', 'How it work'),
            'maturity' => Yii::t('landing', 'IP'),
            'created_at' => Yii::t('landing', 'Created at'),
            'updated_at' => Yii::t('landing', 'Updated at'),
            'deleted_at' => Yii::t('landing', 'Deleted at'),
            'created_by' => Yii::t('landing', 'Created by'),
            'updated_by' => Yii::t('landing', 'Updated by'),
            'deleted_by' => Yii::t('landing', 'Deleted by'),
        ];
    }

}
