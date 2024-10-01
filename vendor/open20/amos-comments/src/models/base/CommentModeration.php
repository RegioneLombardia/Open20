<?php

namespace open20\amos\comments\models\base;

use Yii;

/**
 * This is the base-model class for table "comment_moderation".
 *
 * @property integer $id
 * @property string $classname
 * @property integer $content_id
 * @property integer $moderation
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  CommentModeration extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment_moderation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['classname', 'content_id'], 'required'],
            [['content_id', 'moderation', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['classname'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'classname' => 'Classname',
            'content_id' => 'Content ID',
            'moderation' => 'Moderation',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'deleted_by' => 'Deleted by',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContextModel()
    {
        return $this->hasOne($this->classname::classname(), ['id' => 'content_id']);
    }
}
