<?php

namespace open20\socialwall\models\base;

use Yii;

/**
 * This is the base-model class for table "socialwall_posts_in_socialwall".
 *
 * @property integer $id
 * @property integer $socialwall_id
 * @property integer $socialwall_posts_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  SocialwallPostsInSocialwall extends \open20\amos\core\record\ContentModel
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'socialwall_posts_in_socialwall';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['socialwall_id', 'socialwall_posts_id'], 'required'],
            [['socialwall_id', 'socialwall_posts_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['socialwall_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socialwall::className(), 'targetAttribute' => ['socialwall_id' => 'id']],
            [['socialwall_posts_id'], 'exist', 'skipOnError' => true, 'targetClass' => SocialwallPosts::className(), 'targetAttribute' => ['socialwall_posts_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('socialwall', 'ID'),
            'socialwall_id' => Yii::t('socialwall', 'Related Social Wall'),
            'socialwall_posts_id' => Yii::t('socialwall', 'Socialwall Post id'),
            'created_at' => Yii::t('socialwall', 'Created at'),
            'updated_at' => Yii::t('socialwall', 'Updated at'),
            'deleted_at' => Yii::t('socialwall', 'Deleted at'),
            'created_by' => Yii::t('socialwall', 'Created by'),
            'updated_by' => Yii::t('socialwall', 'Updated at'),
            'deleted_by' => Yii::t('socialwall', 'Deleted at'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [
            'id',
            'socialwall_id',
            'socialwall_posts_id',
        ];
    }


    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return "";
    }


    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        return "";
    }

}
