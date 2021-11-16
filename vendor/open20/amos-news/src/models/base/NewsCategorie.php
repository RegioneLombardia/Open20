<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\models\base
 * @category   CategoryName
 */

namespace open20\amos\news\models\base;

use open20\amos\core\record\Record;
use open20\amos\news\AmosNews;
use yii\helpers\ArrayHelper;

/**
 * Class NewsCategorie
 *
 * This is the base-model class for table "news_categorie".
 *
 * @property integer $id
 * @property string $titolo
 * @property string $sottotitolo
 * @property string $descrizione_breve
 * @property string $descrizione
 * @property string $notify_category
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\news\models\News $news
 * @property \open20\amos\news\models\NewsCategoryRolesMm[] $newsCategoryRolesMms
 * @property \open20\amos\news\models\NewsCategoryCommunityMm[] $newsCategoryCommunityMms
 *
 * @package open20\amos\news\models\base
 */
class NewsCategorie extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_categorie';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titolo'], 'required'],
            [['descrizione'], 'string'],
            [['created_by', 'updated_by', 'deleted_by', 'version','notify_category'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['titolo', 'sottotitolo', 'descrizione_breve'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosNews::t('amosnews', 'Id'),
            'titolo' => AmosNews::t('amosnews', 'Titolo'),
            'sottotitolo' => AmosNews::t('amosnews', 'Sottotitolo'),
            'descrizione_breve' => AmosNews::t('amosnews', 'Descrizione breve'),
            'descrizione' => AmosNews::t('amosnews', 'Descrizione'),
            'notify_category' => AmosNews::t('amosnews', 'Notify category'),
            'created_at' => AmosNews::t('amosnews', 'Creato il'),
            'updated_at' => AmosNews::t('amosnews', 'Aggiornato il'),
            'deleted_at' => AmosNews::t('amosnews', 'Cancellato il'),
            'created_by' => AmosNews::t('amosnews', 'Creato da'),
            'updated_by' => AmosNews::t('amosnews', 'Aggiornato da'),
            'deleted_by' => AmosNews::t('amosnews', 'Cancellato da'),
            'version' => AmosNews::t('amosnews', 'Versione numero')
        ]);
    }

    /**
     * Relation between category and single news.
     * Returns an ActiveQuery related to model News.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(\open20\amos\news\models\News::className(), ['news_categorie_id' => 'id']);
    }

    /**
     * Relation between category and category-roles mm table.
     * Returns an ActiveQuery related to model NewsCategoryRolesMm.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsCategoryRolesMms()
    {
        return $this->hasMany(\open20\amos\news\models\NewsCategoryRolesMm::className(), ['news_category_id' => 'id']);
    }

    /**
     * Relation between category and category-roles mm table.
     * Returns an ActiveQuery related to model NewsCategoryCommunityMm.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsCategoryCommunityMms()
    {
        return $this->hasMany(\open20\amos\news\models\NewsCategoryCommunityMm::className(), ['news_category_id' => 'id']);
    }
}
