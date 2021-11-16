<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\models\base;

use open20\amos\core\record\AmosRecordAudit;
use open20\amos\tag\models\Tag;
use mdm\admin\models\AuthItem;
use Yii;
use open20\amos\tag\AmosTag;

/**
 * This is the base-model class for table "tag_models_auth_items_mm".
 *
 * @property integer $tag_id
 * @property string $classname
 * @property string $auth_item
 *
 * @property Tag $tag
 * @property AuthItem $authItem
 */
class TagModelsAuthItemsMm extends AmosRecordAudit
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag_models_auth_items_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'classname', 'auth_item'], 'required'],
            [['tag_id'], 'integer'],
            [['classname', 'auth_item'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => AmosTag::t('amostag', 'Root'),
            'classname' => AmosTag::t('amostag', 'Model'),
            'auth_item' => AmosTag::t('amostag', 'Ruolo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id'])->inverseOf('tagModelsAuthItemsMms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItem()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'auth_item'])->inverseOf('tagModelsAuthItemsMms');
    }
}