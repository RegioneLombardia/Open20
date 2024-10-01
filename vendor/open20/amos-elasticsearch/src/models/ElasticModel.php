<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */
namespace open20\elasticsearch\models;

use Yii;
use yii\db\ActiveRecord;


class ElasticModel extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%elastic_model}}';
    }

	/**
	 * @inheritdoc
	 */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'deleted_by', 'content_type'], 'integer'],
            [['description', 'classname', 'tags'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'status'], 'string', 'max' => 255],
        ];
    }

	/**
	 * @inheritdoc
	 */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amoselasticsearch', 'ID'),
            'title' => Yii::t('amoselasticsearch', 'Title'),
            'description' => Yii::t('amoselasticsearch', 'Description'),
            'status' => Yii::t('amoselasticsearch', 'Workflow Status'),
            'created_at' => Yii::t('amoselasticsearch', 'Created at'),
            'updated_at' => Yii::t('amoselasticsearch', 'Updated at'),
            'deleted_at' => Yii::t('amoselasticsearch', 'Deleted at'),
            'created_by' => Yii::t('amoselasticsearch', 'Created by'),
            'updated_by' => Yii::t('amoselasticsearch', 'Updated by'),
            'deleted_by' => Yii::t('amoselasticsearch', 'Deleted by'),
        ];
    }
}
