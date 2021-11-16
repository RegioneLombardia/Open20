<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @category   CategoryName
 */

namespace backend\models;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\record\Record;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 * @property string $descriptor
 * @property string $type
 * @property string $value
 * @property string $route
 * @property integer $enabled
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 */
class Settings extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'descriptor', 'type', 'route'], 'required'],
            [['type', 'route'], 'string'],
            [['value', 'name', 'descriptor', 'enabled'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosAdmin::t('amosadmin', 'ID'),
            'name' => AmosAdmin::t('amosadmin', 'Setting Name'),
            'descriptor' => AmosAdmin::t('amosadmin', 'Descriptor Field'),
            'type' => AmosAdmin::t('amosadmin', 'Type of Configuration'),
            'value' => AmosAdmin::t('amosadmin', 'Value of this Configuration)'),
            'route' => AmosAdmin::t('amosadmin', 'Route of the Configuration'),
            'enabled' => AmosAdmin::t('amosadmin', 'Is enabled or not'),
            'created_at' => AmosAdmin::t('amosadmin', 'Created at'),
            'updated_at' => AmosAdmin::t('amosadmin', 'Updated at'),
            'deleted_at' => AmosAdmin::t('amosadmin', 'Deleted at'),
            'created_by' => AmosAdmin::t('amosadmin', 'Created by'),
            'updated_by' => AmosAdmin::t('amosadmin', 'Updated by'),
            'deleted_by' => AmosAdmin::t('amosadmin', 'Deleted by'),
        ];
    }
}