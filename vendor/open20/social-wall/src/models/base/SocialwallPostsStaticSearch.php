<?php

namespace open20\socialwall\models\base;

use Yii;

/**
 * This is the base-model class for table "socialwall_posts_search".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $context
 * @property string $json_data_content
 * @property string $search_tags
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  SocialwallPostsStaticSearch extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'socialwall_posts_search';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['json_data_content'], 'required'],
            [['json_data_content'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['context'], 'string', 'max' => 255],
            [['search_tags'], 'string', 'max' => 600],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('socialwall', 'ID'),
            'user_id' => Yii::t('socialwall', 'User id'),
            'context' => Yii::t('socialwall', 'Search context'),
            'json_data_content' => Yii::t('socialwall', 'Json Data Fetched'),
            'search_tags' => Yii::t('socialwall', 'Search tags'),
            'created_at' => Yii::t('socialwall', 'Created at'),
            'updated_at' => Yii::t('socialwall', 'Updated at'),
            'deleted_at' => Yii::t('socialwall', 'Deleted at'),
            'created_by' => Yii::t('socialwall', 'Created by'),
            'updated_by' => Yii::t('socialwall', 'Updated at'),
            'deleted_by' => Yii::t('socialwall', 'Deleted at'),
        ];
    }
}
