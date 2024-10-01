<?php

namespace open20\amos\utility\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;


/**
 * This is the model class for table "update_contents".
 *
 * @property int $id
 * @property string $module Module with update contents
 * @property int $updates Some updates here!
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at Cancellato il
 * @property int $created_by Creato da
 * @property int $updated_by Aggiornato da
 * @property int $deleted_by Cancellato da
 */
class UpdateContents extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'update_contents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['updates', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['module'], 'string', 'max' => 32],
            [['module'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module' => 'Module',
            'updates' => 'Updates',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
        ];
    }
}
