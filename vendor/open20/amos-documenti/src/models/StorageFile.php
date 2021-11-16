<?php

namespace open20\amos\documenti\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "admin_storage_file".
 */
final class StorageFile extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        // call parent
        parent::init();

    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_storage_file';
    }
    
    /**
     * @inheritdoc
     */
    public static function find()
    {
        return parent::find();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_original', 'name_new', 'mime_type', 'name_new_compound', 'extension', 'hash_file', 'hash_name'], 'required'],
            [['folder_id', 'file_size', 'is_deleted'], 'safe'],
            [['is_hidden'], 'boolean'],
            [['inline_disposition', 'upload_timestamp', 'upload_user_id'], 'integer'],
            [['caption'], 'string'],
        ];
    }
}
