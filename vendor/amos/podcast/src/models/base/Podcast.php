<?php

namespace amos\podcast\models\base;

use amos\podcast\Module;
use Yii;

/**
 * This is the base-model class for table "podcast".
 *
 * @property integer $id
 * @property string $title
 * @property string $status
 * @property string $description
 * @property string $image_description
 * @property integer $podcast_category_id
 * @property integer $community_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  Podcast extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'podcast';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description', 'image_description'], 'string'],
            [['community_id', 'podcast_category_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['status','created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['podcast_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => PodcastCategory::className(), 'targetAttribute' => ['podcast_category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amospodcast', 'ID'),
            'title' => Module::t('amospodcast', 'Title'),
            'description' => Module::t('amospodcast', 'Description'),
            'image_description' => Module::t('amospodcast', 'Image description'),
            'podcast_category_id' => Module::t('amospodcast', 'Category'),
            'created_at' => Module::t('amospodcast', 'Created at'),
            'updated_at' => Module::t('amospodcast', 'Updated at'),
            'deleted_at' => Module::t('amospodcast', 'Deleted at'),
            'created_by' => Module::t('amospodcast', 'Created by'),
            'updated_by' => Module::t('amospodcast', 'Updated at'),
            'deleted_by' => Module::t('amospodcast', 'Deleted at'),
        ];
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPodcastCategory()
    {
        return $this->hasOne(\amos\podcast\models\PodcastCategory::className(), [ 'id' => 'podcast_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPodcastEpisodes()
    {
        return $this->hasMany(\amos\podcast\models\PodcastEpisode::className(), [ 'podcast_id' => 'id' ]);
    }


}
