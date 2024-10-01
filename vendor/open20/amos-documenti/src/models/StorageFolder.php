<?php

namespace open20\amos\documenti\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "admin_storage_folder".
 *
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property int $timestamp_create
 * @property int $is_deleted
 *
 */
final class StorageFolder extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public function init() {
        // call parent
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'admin_storage_folder';
    }

    /**
     * @inheritdoc
     */
    public static function find() {
        return parent::find()->orderBy(['name' => 'ASC']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['parent_id', 'timestamp_create'], 'integer'],
            [['is_deleted'], 'boolean'],
            [['name'], 'string', 'max' => 255],
        ];
    }

}
