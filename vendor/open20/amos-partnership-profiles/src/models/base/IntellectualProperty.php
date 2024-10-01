<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\models\base
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\models\base;

use open20\amos\core\record\Record;
use open20\amos\partnershipprofiles\Module;
use yii\helpers\ArrayHelper;

/**
 * Class IntellectualProperty
 *
 * This is the base-model class for table "intellectual_property".
 *
 * @property integer $id
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\partnershipprofiles\models\PartnershipProfiles[] $partnershipProfiles
 *
 * @package open20\amos\partnershipprofiles\models\base
 */
class IntellectualProperty extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'intellectual_property';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Module::t('amospartnershipprofiles', 'ID'),
            'value' => Module::t('amospartnershipprofiles', 'Value'),
            'created_at' => Module::t('amospartnershipprofiles', 'Created at'),
            'updated_at' => Module::t('amospartnershipprofiles', 'Updated at'),
            'deleted_at' => Module::t('amospartnershipprofiles', 'Deleted at'),
            'created_by' => Module::t('amospartnershipprofiles', 'Created by'),
            'updated_by' => Module::t('amospartnershipprofiles', 'Updated by'),
            'deleted_by' => Module::t('amospartnershipprofiles', 'Deleted by')
        ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnershipProfiles()
    {
        return $this->hasMany(Module::instance()->model('PartnershipProfiles'), ['intellectual_property_id' => 'id']);
    }
}
