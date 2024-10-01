<?php

namespace open20\amos\emailmanager\models\base;

use open20\amos\core\record\Record;
use Yii;

/**
 * This is the base-model class for table "email_view".
 *
 * @property integer $id
 * @property string $module
 * @property string $view
 * @property string $content
 * @property string $params
 * @property integer $created_at
 * @property integer $updated_at
 */
class  EmailView extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_view';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'params', 'description'], 'string'],
            [['module', 'view', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'module' => Yii::t('app', 'Plugin'),
            'view' => Yii::t('app', 'Tipologia'),
            'content' => Yii::t('app', 'Content'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'params' => Yii::t('app', 'Params'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
