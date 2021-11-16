<?php

namespace open20\amos\news\models\base;

use Yii;

/**
 * This is the base-model class for table "news_category_community_mm".
 *
 * @property integer $id
 * @property integer $news_category_id
 * @property string $community_id
 * @property string $visible_to_cm
 * @property string $visible_to_participant
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\news\models\NewsCategorie $newsCategory
 */
class  NewsCategoryCommunityMm extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_category_community_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_category_id', 'community_id'], 'required'],
            [['news_category_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['visible_to_cm', 'visible_to_participant', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['community_id'], 'string', 'max' => 255],
            [['news_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\news\models\NewsCategorie::className(), 'targetAttribute' => ['news_category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosnews', 'ID'),
            'news_category_id' => Yii::t('amosnews', 'News Category ID'),
            'community_id' => Yii::t('amosnews', 'Community'),
            'created_at' => Yii::t('amosnews', 'Created at'),
            'updated_at' => Yii::t('amosnews', 'Updated at'),
            'deleted_at' => Yii::t('amosnews', 'Deleted at'),
            'created_by' => Yii::t('amosnews', 'Created by'),
            'updated_by' => Yii::t('amosnews', 'Updated by'),
            'deleted_by' => Yii::t('amosnews', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsCategory()
    {
        return $this->hasOne(\open20\amos\news\models\NewsCategorie::className(), ['id' => 'news_category_id']);
    }
}
