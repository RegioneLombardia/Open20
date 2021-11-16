<?php

namespace backend\modules\landings\models\base;

use open20\amos\documenti\models\Documenti;
use Yii;

/**
 * This is the base-model class for table "PreferenceLandingDocumenti".
 *
 * @property integer $id
 * @property integer $preference_landing_id
 * @property integer $documenti_id
 * @property integer $sort_value
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\documenti\models\Documenti $documenti
 * @property \backend\modules\landings\models\PreferenceLanding $preferenceLanding
 */
class  PreferenceLandingDocumenti extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preference_landing_documenti';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['preference_landing_id', 'documenti_id', 'sort_value', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['documenti_id'], 'exist', 'skipOnError' => true, 'targetClass' => Documenti::className(), 'targetAttribute' => ['news_id' => 'id']],
            [['preference_landing_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\modules\landings\models\PreferenceLanding::className(), 'targetAttribute' => ['preference_landing_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('preferencelandings', 'ID'),
            'preference_landing_id' => Yii::t('preferencelandings', 'Landing'),
            'sort_value' => Yii::t('preferencelandings', 'Ordinamento'),
            'documenti_id' => Yii::t('preferencelandings', 'Name'),
            'created_at' => Yii::t('preferencelandings', 'Created at'),
            'updated_at' => Yii::t('preferencelandings', 'Updated at'),
            'deleted_at' => Yii::t('preferencelandings', 'Deleted at'),
            'created_by' => Yii::t('preferencelandings', 'Created by'),
            'updated_by' => Yii::t('preferencelandings', 'Updated at'),
            'deleted_by' => Yii::t('preferencelandings', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumenti()
    {
        return $this->hasOne(Documenti::className(), ['id' => 'documenti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceLanding()
    {
        return $this->hasOne(\backend\modules\landings\models\PreferenceLanding::className(), ['id' => 'preference_landing_id']);
    }
}
