<?php

namespace open20\amos\utility\models\base;

use Yii;

/**
 * This is the base-model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \open20\amos\utility\models\AuthAssignment[] $authAssignments
 * @property \open20\amos\utility\models\AuthRule $ruleName
 * @property \open20\amos\utility\models\AuthItemChild[] $authItemChildren
 * @property \open20\amos\utility\models\AuthItemChild[] $authItemChildren0
 * @property \open20\amos\utility\models\AuthItem[] $children
 * @property \open20\amos\utility\models\AuthItem[] $parents
 * @property \open20\amos\utility\models\CwhTagInterestMm[] $cwhTagInterestMms
 * @property \open20\amos\utility\models\TagModelsAuthItemsMm[] $tagModelsAuthItemsMms
 */
class  AuthItem extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('amosutility', 'Name'),
            'type' => Yii::t('amosutility', 'Type'),
            'description' => Yii::t('amosutility', 'Description'),
            'rule_name' => Yii::t('amosutility', 'Rule Name'),
            'data' => Yii::t('amosutility', 'Data'),
            'created_at' => Yii::t('amosutility', 'Created At'),
            'updated_at' => Yii::t('amosutility', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return null;
        //TO-DO
        //return $this->hasMany(\open20\amos\utility\models\AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(\open20\amos\utility\models\AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(\open20\amos\utility\models\AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(\open20\amos\utility\models\AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(\open20\amos\utility\models\AuthItem::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(\open20\amos\utility\models\AuthItem::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhTagInterestMms()
    {
        return $this->hasMany(\open20\amos\cwh\models\CwhTagInterestMm::className(), ['auth_item' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagModelsAuthItemsMms()
    {
        return $this->hasMany(\open20\amos\tag\models\TagModelsAuthItemsMm::className(), ['auth_item' => 'name']);
    }
}
