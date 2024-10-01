<?php

namespace open20\amos\news\models\base;

use  open20\amos\news\models\News;
use  open20\amos\documenti\models\Documenti;

use Yii;

/**
 * This is the base-model class for table "news_related_documenti_mm".
 *
 * @property integer $id
 * @property integer $news_id
 * @property integer $related_documenti_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \app\models\News $news
 * @property \app\models\Documenti $relatedDocumenti
 */
class NewsRelatedDocumentiMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return 'news_related_documenti_mm';
    }

	/**
	 * @inheritdoc
	 */
    public function rules()
    {
        return [
            [['news_id', 'related_documenti_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
            [['related_documenti_id'], 'exist', 'skipOnError' => true, 'targetClass' => Documenti::className(), 'targetAttribute' => ['related_documenti_id' => 'id']],
        ];
    }

	/**
	 * @inheritdoc
	 */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'news_id' => Yii::t('app', 'News'),
            'related_documenti_id' => Yii::t('app', 'Related News'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'deleted_at' => Yii::t('app', 'Deleted at'),
            'created_by' => Yii::t('app', 'Created at'),
            'updated_by' => Yii::t('app', 'Updated by'),
            'deleted_by' => Yii::t('app', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(\open20\amos\news\models\News::className(), ['id' => 'news_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedDocumenti()
    {
        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'related_documenti_id']);
    }
}
