<?php

namespace open20\onlyoffice\models\base;

use Yii;

/**
 * This is the base-model class for table "onlyoffice_files".
 *
 * @property integer $id
 * @property integer $attach_file_id
 * @property integer $link_diretto
 * @property integer $in_modifica
 * @property integer $last_status
 * @property integer $creato_il
 * @property integer $creato_da
 * @property string $open_in_mod_at
 * @property integer $open_in_mod_by
 * @property string $modified_at
 * @property integer $modified_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class OnlyofficeFiles extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'onlyoffice_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'attach_file_id', 'last_status', 'creato_il', 'creato_da',
                    'open_in_mod_at', 'open_in_mod_by', 'modified_at',
                    'modified_by',
                ], 'default', 'value' => null
            ],
            [['link_diretto'], 'default', 'value' => 1],
            [['in_modifica'], 'default', 'value' => 0],
            [
                [
                    'attach_file_id', 'link_diretto', 'creato_da',
                    'open_in_mod_by', 'modified_by', 'created_by', 'updated_by',
                    'deleted_by'
                ], 'integer'
            ],
            [['last_status'], 'integer', 'min' => 0],
            [['in_modifica'], 'boolean'],
            [
                [
                    'creato_da', 'open_in_mod_at', 'modified_at', 'created_at',
                    'updated_at', 'deleted_at'
                ], 'safe'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attach_file_id' => 'Attach File ID',
            'link_diretto' => 'Link Diretto',
            'in_modifica' => 'File in modifica',
            'last_status' => 'Ultimo stato',
            'creato_il' => 'File creato il',
            'creato_da' => 'File creato da',
            'open_in_mod_at' => 'Aperto in modifica il',
            'open_in_mod_by' => 'Aperto in modifica da',
            'modified_at' => 'Modificato il',
            'modified_by' => 'Modificato da',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
        ];
    }
}