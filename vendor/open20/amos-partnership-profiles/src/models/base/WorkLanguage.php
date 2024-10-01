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
 * Class WorkLanguage
 *
 * This is the base-model class for table "work_language".
 *
 * @property integer $id
 * @property string $work_language_code
 * @property string $work_language
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
class WorkLanguage extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'work_language';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['work_language_code', 'work_language'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['work_language_code'], 'string', 'max' => 5],
            [['work_language'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Module::t('amospartnershipprofiles', 'ID'),
            'work_language_code' => Module::t('amospartnershipprofiles', 'Work Language Code'),
            'work_language' => Module::t('amospartnershipprofiles', 'Work Language'),
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
        return $this->hasMany(Module::instance()->model('PartnershipProfiles'), ['work_language_id' => 'id']);
    }
}
