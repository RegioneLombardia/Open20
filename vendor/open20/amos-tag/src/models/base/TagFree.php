<?php

namespace open20\amos\tag\models\base;

use Yii;

/**
 * This is the base-model class for table "tag_free".
 *
 * @property integer $id
 * @property string $tag
 * @property integer $group_id
 * @property string $url
 * @property integer $counter
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class TagFree extends \open20\amos\core\record\Record {

    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tag_free';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tag'], 'required'],
            [['group_id', 'counter', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['url'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['tag'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('amostag', 'ID'),
            'tag' => Yii::t('amostag', 'Tag'),
            'group_id' => Yii::t('amostag', 'Group ID'),
            'url' => Yii::t('amostag', 'Url'),
            'counter' => Yii::t('amostag', 'Counter'),
            'created_at' => Yii::t('amostag', 'Created at'),
            'updated_at' => Yii::t('amostag', 'Updated at'),
            'deleted_at' => Yii::t('amostag', 'Deleted at'),
            'created_by' => Yii::t('amostag', 'Created by'),
            'updated_by' => Yii::t('amostag', 'Updated by'),
            'deleted_by' => Yii::t('amostag', 'Deleted by'),
        ];
    }

}
