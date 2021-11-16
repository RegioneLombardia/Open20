<?php

namespace open20\amos\seo\models\base;

use Yii;

/**
 * This is the base-model class for table "seo_data".
 *
 * @property integer $id
 * @property string $classname
 * @property integer $content_id
 * @property string $pretty_url
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $og_title
 * @property string $og_description
 * @property string $og_type
 * @property string $meta_robots
 * @property string $meta_googlebot
 * @property string $unavailable_after_date
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class SeoData extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['meta_title', 'meta_description', 'meta_keywords', 'og_title', 'og_description'], 'string'],
            [['unavailable_after_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['classname', 'pretty_url', 'og_type', 'meta_robots', 'meta_googlebot'], 'string', 'max' => 255],
            [['classname', 'content_id'], 'unique', 'targetAttribute' => ['classname', 'content_id']],
            [['pretty_url'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'classname' => Yii::t('app', 'Classname'),
            'content_id' => Yii::t('app', 'Content ID'),
            'pretty_url' => Yii::t('app', 'Pretty Url'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
            'og_title' => Yii::t('app', 'Og Title'),
            'og_description' => Yii::t('app', 'Og Description'),
            'og_type' => Yii::t('app', 'Og Type'),
            'meta_robots' => Yii::t('app', 'Meta Robots'),
            'meta_googlebot' => Yii::t('app', 'Meta Googlebot'),
            'unavailable_after_date' => Yii::t('app', 'Unavailable After Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }

}
