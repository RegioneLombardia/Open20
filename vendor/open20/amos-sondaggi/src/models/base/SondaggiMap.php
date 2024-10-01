<?php

namespace open20\amos\sondaggi\models\base;

use Yii;
use open20\amos\core\record\Record;
use open20\amos\sondaggi\AmosSondaggi;

/**
 * This is the base-model class for table "sondaggi_content_model".
 *
 * @property integer $id
 * @property string $class_name
 * @property string $field_name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class SondaggiMap extends Record
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sondaggi_map';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'deleted_by', 'obbligatorio'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['campo', 'tabella'], 'string', 'max' => 255],
            [['descrizione'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosSondaggi::t('amossondaggi', 'ID'),
            'campo' => AmosSondaggi::t('amossondaggi', 'Campo'),
            'tabella' => AmosSondaggi::t('amossondaggi', 'Tabella'),
            'descrizione' => AmosSondaggi::t('amossondaggi', 'Descrizione'),
            'obbligatorio' => AmosSondaggi::t('amossondaggi', 'Obbligatorio'),
            'created_at' => AmosSondaggi::t('amossondaggi', 'Created At'),
            'updated_at' => AmosSondaggi::t('amossondaggi', 'Updated At'),
            'deleted_at' => AmosSondaggi::t('amossondaggi', 'Deleted At'),
            'created_by' => AmosSondaggi::t('amossondaggi', 'Created By'),
            'updated_by' => AmosSondaggi::t('amossondaggi', 'Updated By'),
            'deleted_by' => AmosSondaggi::t('amossondaggi', 'Deleted By'),
        ];
    }
}