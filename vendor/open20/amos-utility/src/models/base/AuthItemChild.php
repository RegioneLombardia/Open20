<?php

namespace open20\amos\utility\models\base;

use Yii;

/**
 * This is the base-model class for table "auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property \open20\amos\utility\models\AuthItem $parent0
 * @property \open20\amos\utility\models\AuthItem $child0
 */
class  AuthItemChild extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 255],
            [['parent', 'child'], 'unique', 'targetAttribute' => ['parent', 'child']],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['parent' => 'name']],
            [['child'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['child' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent' => Yii::t('amosutility', 'Parent'),
            'child' => Yii::t('amosutility', 'Child'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(\open20\amos\utility\models\AuthItem::className(), ['name' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild()
    {
        return $this->hasOne(\open20\amos\utility\models\AuthItem::className(), ['name' => 'child']);
    }
}
