<?php

namespace open20\cms\dashboard\models\base;

use open20\cms\dashboard\models\CmsDashSidebarItem;
/**
 * This is the base-model class for table "cms_dash_sidebar_item_roles_mm".
 *
 * @property integer $id
 * @property integer $id_item
 * @property integer $role
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class CmsDashSidebarItemRolesMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_dash_sidebar_item_roles_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_item', 'role'], 'required'],
            [['id_item', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['role'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['id_item'], 'exist', 'skipOnError' => true, 'targetClass' => CmsDashSidebarItem::class, 'targetAttribute' => ['id_item' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_item' => 'ID elemento',
            'role' => 'Ruolo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsDashSidebarItem()
    {
        return $this->hasOne(CmsDashSidebarItem::class, ['id' => 'id_item']);
    }
}