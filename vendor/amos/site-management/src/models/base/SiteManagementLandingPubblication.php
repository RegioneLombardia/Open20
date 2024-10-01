<?php

namespace amos\sitemanagement\models\base;

use Yii;

/**
 * This is the base-model class for table "site_management_landing_pubblication".
 *
 * @property integer $id
 * @property string $url
 * @property integer $landing_id
 * @property string $start_date
 * @property string $end_date
 * @property string $start_time
 * @property string $end_time
 * @property integer expire_days_cookie
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \amos\sitemanagement\models\SiteManagementLandingPubblication $landing
 * @property \amos\sitemanagement\models\SiteManagementLandingPubblication[] $siteManagementLandingPubblications
 */
class SiteManagementLandingPubblication extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_landing_pubblication';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'landing_id'], 'required'],
            [['landing_id', 'expire_days_cookie', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['start_date', 'end_date', 'start_time', 'end_time', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['url'], 'string', 'max' => 255],
            [['landing_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementLanding::className(), 'targetAttribute' => ['landing_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossitemanagement', 'ID'),
            'url' => Yii::t('amossitemanagement', 'Url target'),
            'landing_id' => Yii::t('amossitemanagement', 'Landing'),
            'start_date' => Yii::t('amossitemanagement', 'Start date'),
            'end_date' => Yii::t('amossitemanagement', 'End date'),
            'start_time' => Yii::t('amossitemanagement', 'Start time'),
            'end_time' => Yii::t('amossitemanagement', 'End time'),
            'expire_days_cookie' => Yii::t('amossitemanagement', 'Expire days cookie'),
            'created_at' => Yii::t('amossitemanagement', 'Created at'),
            'updated_at' => Yii::t('amossitemanagement', 'Updated at'),
            'deleted_at' => Yii::t('amossitemanagement', 'Deleted at'),
            'created_by' => Yii::t('amossitemanagement', 'Created by'),
            'updated_by' => Yii::t('amossitemanagement', 'Updated at'),
            'deleted_by' => Yii::t('amossitemanagement', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanding()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementLanding::className(), ['id' => 'landing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementLandingPubblications()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManagementLandingPubblication::className(), ['landing_id' => 'id']);
    }
}
