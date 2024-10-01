<?php

namespace open20\video\models\base;

use open20\amos\community\models\Community;
use open20\amos\core\record\ContentModel;
use Yii;

/**
 * This is the base-model class for table "formazione".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $description
 * @property integer $in_evidenza
 * @property integer $community_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property open20\amos\community\models\Community $community
 */
class Video extends ContentModel
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url', 'description'], 'required'],
            [['description'], 'string'],
            [['in_evidenza', 'community_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'url'], 'string', 'max' => 255],
            [['url'], 'url'],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => Community::className(), 'targetAttribute' => ['community_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('video', 'ID'),
            'title' => Yii::t('video', 'Title'),
            'url' => Yii::t('video', 'Url'),
            'description' => Yii::t('video', 'Description'),
            'in_evidenza' => Yii::t('video', 'In Evidenza'),
            'community_id' => Yii::t('video', 'Community ID'),
            'created_at' => Yii::t('video', 'Created At'),
            'updated_at' => Yii::t('video', 'Updated At'),
            'deleted_at' => Yii::t('video', 'Deleted At'),
            'created_by' => Yii::t('video', 'Created By'),
            'updated_by' => Yii::t('video', 'Updated By'),
            'deleted_by' => Yii::t('video', 'Deleted By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity()
    {
        return $this->hasOne(Community::className(), ['id' => 'community_id']);
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        $ret = $this->description;

        if ($truncate) {
            $ret = $this->__shortText($this->description, 50);
        }

        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [];
    }
}
