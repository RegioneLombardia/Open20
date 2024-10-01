<?php

namespace open20\amos\news\models\base;

use Yii;

/**
* This is the base-model class for table "news_categorie_mm".
*
    * @property integer $id
    * @property integer $news_id
    * @property integer $news_categorie_id
    * @property string $created_at
    * @property string $updated_at
    * @property string $deleted_at
    * @property integer $created_by
    * @property integer $updated_by
    * @property integer $deleted_by
    *
            * @property \open20\amos\news\models\NewsCategorie $newsCategorie
            * @property \open20\amos\news\models\News $news
    */
 class  NewsCategorieMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

/**
* @inheritdoc
*/
public static function tableName()
{
return 'news_categorie_mm';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['news_id', 'news_categorie_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['news_categorie_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsCategorie::className(), 'targetAttribute' => ['news_categorie_id' => 'id']],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('amosnews', 'ID'),
    'news_id' => Yii::t('amosnews', 'News'),
    'news_categorie_id' => Yii::t('amosnews', 'Category'),
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
    public function getNewsCategorie()
    {
    return $this->hasOne(\open20\amos\news\models\NewsCategorie::className(), ['id' => 'news_categorie_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getNews()
    {
    return $this->hasOne(\open20\amos\news\models\News::className(), ['id' => 'news_id']);
    }
}
