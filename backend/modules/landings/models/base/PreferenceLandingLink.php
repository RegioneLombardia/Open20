<?php

namespace backend\modules\landings\models\base;

use Yii;

/**
 * This is the base-model class for table "preference_landing_rating".
 *
 * @property integer $id
 * @property integer $preference_landing_id
 * @property integer $sort_value
 * @property string $url
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class PreferenceLandingLink extends \open20\amos\core\record\Record
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preference_landing_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['preference_landing_id', 'created_by', 'sort_value', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'url'], 'string', 'max' => 255],
            [['title', 'url'], 'required'],
            [['url'], 'url'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('landing', 'ID'),
            'preference_landing_id' => Yii::t('landing', 'Landing'),
            'sort_value' => Yii::t('landing', 'Ordinamento'),
            'title' => Yii::t('landing', 'Titolo'),
            'url' => Yii::t('landing', 'Link'),
            'created_at' => Yii::t('landing', 'Created at'),
            'updated_at' => Yii::t('landing', 'Updated at'),
            'deleted_at' => Yii::t('landing', 'Deleted at'),
            'created_by' => Yii::t('landing', 'Created by'),
            'updated_by' => Yii::t('landing', 'Updated by'),
            'deleted_by' => Yii::t('landing', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceLanding()
    {
        return $this->hasOne(\backend\modules\landings\models\PreferenceLanding::className(), ['id' => 'preference_landing_id']);
    }

    public function getLandingMaxSortValue()
    {
        if (!empty($this->preference_landing_id)){
            $max = self::find()->andWhere(['preference_landing_id' => $this->preference_landing_id])->max('sort_value');
            return intval( $max );
        }
        return null;
    }

}
