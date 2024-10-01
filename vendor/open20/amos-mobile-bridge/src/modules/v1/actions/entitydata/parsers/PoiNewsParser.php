<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\models\ContentShared;
use open20\amos\events\models\Event;
use open20\amos\news\models\base\News as News2;
use open20\amos\news\models\News;
use open20\amos\news\models\search\NewsSearch;
use open20\amos\tag\models\Tag;
use Yii;
use yii\db\Expression;
use yii\helpers\StringHelper;

class PoiNewsParser extends NewsParser
{

    /**
     * Get all news
     * @param $namespace
     * @param $bodyParams
     * @return array
     */
    public static function getItemsForPreference($namespace, $bodyParams)
    {
        //Paginated offset
        $offset = $bodyParams['offset'] - 1;

        //Check limit is set
        $limit = (int)$bodyParams['limit'] ?: 20;

        //Instance search model
        $newsSearch = new NewsSearch();

        //Use search data provider
        $dataProvider = $newsSearch->searchAll([]);
        $dataProvider->query
            ->andWhere(['>=', 'data_pubblicazione', new Expression("DATE_SUB(NOW(), INTERVAL 2 MONTH)")])
            ->andWhere(['primo_piano' => 1]);

        //Set Limit and offsets
        $dataProvider->pagination->setPageSize($limit);
        $dataProvider->pagination->setPage($offset);

        //Fetch news and parse it
        $items = $dataProvider->getModels();

        //Resulting array of items
        $itemsArray = [];

        foreach ($items as $item) {
            $newItem = self::parseItemForPrefrence($item);

            if (!empty($newItem)) {
                //Insert New Item
                $itemsArray[] = $newItem;
            }
        }

        return $itemsArray;
    }


    /**
     * Parse single news and return an api designed array
     * @param $item
     * @return array
     */
    public static function parseItemForPrefrence($item)
    {
        //The base class name
        $baseClassName = StringHelper::basename(News2::className());

        //Read permission name
        $readPremission = strtoupper($baseClassName . '_READ');

        //Can user view element
        $canView = Yii::$app->user->can($readPremission, ['model' => $item]);

        if ($canView) {
            //Define temp item
            $newItem = [];

            //Need id column
            $newItem['id'] = $item->id;

            //Get the list of description fields
            $newItem['representingColumn'] = $item->representingColumn();

            //Creator profile
            $owner = UserProfile::find()->andWhere(['user_id' => $item->created_by])->one();

            //Image
            $image = $item->newsImage;

            //main category
            $nameMainCategory = '';
            $newsCategory = $item->newsCategorie;
            if ($newsCategory) {
                $nameMainCategory = $newsCategory->titolo;
            }
            //other categories
            $stringOtherCategories = '';
            $otherCategories = $item->otherNewsCategories;
            $otherCategoriesNames = [];
            foreach ($otherCategories as $category) {
                $otherCategoriesNames[] = $category->titolo;
            }
            if (!empty($otherCategoriesNames)) {
                $stringOtherCategories = implode(', ', $otherCategoriesNames);
            }
            $item->usePrettyUrl = true;

            //Fill fields from item usable in app
            $newItem['fields'] = [
                'titolo' => self::flushHtml($item->titolo),
                'sottotitolo' => self::flushHtml($item->sottotitolo),
                'descrizione_breve' => self::flushHtml($item->descrizione_breve),
                'descrizione' => self::flushHtml($item->descrizione),
                'data_pubblicazione' => $item->data_pubblicazione,
                'highlights' => $item->in_evidenza,
                'main_category' => $nameMainCategory,
                'other_categories' => $stringOtherCategories,
                'tags' => self::getTags($item),
                'newsImageUrl' => $image ? Yii::$app->getUrlManager()->createAbsoluteUrl($image->getWebUrl()) : null,
                'detailUrl' => Yii::$app->getUrlManager()->createAbsoluteUrl($item->getFullViewUrl()),
            ];

            //Remove id as is not needed
            if(isset($newItem['fields']['id'])){
                unset($newItem['fields']['id']);
            }
            return $newItem;
        }

        return [];
    }

    /**
     * @param $news
     * @return array
     */
    public static function getTags($news)
    {
        $preferenceTags = [];
        $tags = Tag::find()
            ->innerJoin('entitys_tags_mm', 'entitys_tags_mm.tag_id = tag.id')
            ->andWhere(['record_id' => $news->id])
            ->all();
        foreach ($tags as $tag) {
            $preferenceTags [] = ['id' => $tag->id, 'name' => $tag->nome, 'codice' => $tag->codice];
        }
        return $preferenceTags;
    }

}
