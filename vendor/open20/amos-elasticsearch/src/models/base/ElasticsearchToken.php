<?php

namespace open20\elasticsearch\models\base;

use Yii;

/**
 * This is the base-model class for table "elasticsearch_token".
 *
 * @property integer $id
 * @property string $token
 * @property integer $time
 * @property integer $consumed 
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class ElasticsearchToken extends \open20\amos\core\record\Record {

    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'elasticsearch_token';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['token', 'url'], 'string'],
            [['time', 'consumed', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('amoselasticsearch', 'ID'),
            'token' => Yii::t('amoselasticsearch', 'Token'),
            'url' => Yii::t('amoselasticsearch', 'Url'),
            'time' => Yii::t('amoselasticsearch', 'Time'),
            'consumed' => Yii::t('amoselasticsearch', 'Consumed'),
            'created_at' => Yii::t('amoselasticsearch', 'Searched at'),
            'updated_at' => Yii::t('amoselasticsearch', 'Updated at'),
            'deleted_at' => Yii::t('amoselasticsearch', 'Deleted at'),
            'created_by' => Yii::t('amoselasticsearch', 'Created by'),
            'updated_by' => Yii::t('amoselasticsearch', 'Updated by'),
            'deleted_by' => Yii::t('amoselasticsearch', 'Deleted by'),
        ];
    }
}
