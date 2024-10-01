<?php

namespace open20\amos\tag\models\base;

use Yii;

/**
 * This is the base-model class for table "tag_free_model_mm".
 *
 * @property integer $id
 * @property integer $tag_id
 * @property integer $model_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\tag\models\TagFree $tag
 */
class TagFreeModelMm extends \open20\amos\core\record\Record {

    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tag_free_model_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tag_id', 'model_id', 'content_id'], 'required'],
            [['tag_id', 'model_id', 'content_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\tag\models\TagFree::className(), 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('amostag', 'ID'),
            'tag_id' => Yii::t('amostag', 'Tag ID'),
            'model_id' => Yii::t('amostag', 'Model ID'),
            'content_id' => Yii::t('amostag', 'Content ID'),
            'created_at' => Yii::t('amostag', 'Created at'),
            'updated_at' => Yii::t('amostag', 'Updated at'),
            'deleted_at' => Yii::t('amostag', 'Deleted at'),
            'created_by' => Yii::t('amostag', 'Created by'),
            'updated_by' => Yii::t('amostag', 'Updated by'),
            'deleted_by' => Yii::t('amostag', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag() {
        return $this->hasOne(\open20\amos\tag\models\TagFree::className(), ['id' => 'tag_id']);
    }

}
