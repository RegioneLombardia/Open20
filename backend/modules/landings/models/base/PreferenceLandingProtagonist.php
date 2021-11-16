<?php

namespace backend\modules\landings\models\base;

use backend\modules\landings\Module;
use Yii;

/**
 * This is the base-model class for table "preference_landing_protagonist".
 *
 * @property integer $id
 * @property integer $preference_landing_id
 * @property string $name
 * @property string $surname
 * @property string $company
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \backend\modules\landings\models\PreferenceLanding $preferenceLanding
 */
class  PreferenceLandingProtagonist extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preference_landing_protagonist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['preference_landing_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'surname', 'company'], 'string', 'max' => 255],
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
            'name' => Module::t('preferencelandings', 'Name'),
            'surname' => Module::t('preferencelandings', 'Surname'),
            'company' => Module::t('preferencelandings', 'Company'),
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
    public function getPreferenceLanding()
    {
        return $this->hasOne(\backend\modules\landings\models\PreferenceLanding::className(), ['id' => 'preference_landing_id']);
    }
}
