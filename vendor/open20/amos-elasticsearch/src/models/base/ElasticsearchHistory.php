<?php

namespace open20\elasticsearch\models\base;

use open20\amos\core\user\User;
use Yii;

/**
 * This is the base-model class for table "elasticsearch_history".
 *
 * @property integer $id
 * @property string $search_text
 * @property string $results
 * @property integer tot_found
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property User $user
 */
class  ElasticsearchHistory extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elasticsearch_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['results'], 'string'],
            [['tot_found', 'user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['search_text'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amoselasticsearch', 'ID'),
            'search_text' => Yii::t('amoselasticsearch', 'Search'),
            'results' => Yii::t('amoselasticsearch', 'Result'),
            'user_id' => Yii::t('amoselasticsearch', 'User'),
            'created_at' => Yii::t('amoselasticsearch', 'Searched at'),
            'updated_at' => Yii::t('amoselasticsearch', 'Updated at'),
            'deleted_at' => Yii::t('amoselasticsearch', 'Deleted at'),
            'created_by' => Yii::t('amoselasticsearch', 'Created by'),
            'updated_by' => Yii::t('amoselasticsearch', 'Updated by'),
            'deleted_by' => Yii::t('amoselasticsearch', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
