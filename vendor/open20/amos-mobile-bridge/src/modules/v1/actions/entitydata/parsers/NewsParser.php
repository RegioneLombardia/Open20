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
use open20\amos\news\models\base\News as News2;
use open20\amos\news\models\News;
use open20\amos\news\models\search\NewsSearch;
use Yii;
use yii\helpers\StringHelper;

class NewsParser extends BaseParser
{

    /**
     * Get all news
     * @param $namespace
     * @param $bodyParams
     * @return array
     */
    public static function getItems($namespace, $bodyParams)
    {
        //Paginated offset
        $offset = $bodyParams['offset'] - 1;

        //Check limit is set
        $limit = (int)$bodyParams['limit'] ?: 20;

        //Instance search model
        $newsSearch = new NewsSearch();

        //Use search data provider
        $dataProvider = $newsSearch->searchOwnInterest([]);

        //Set Limit and offsets
        $dataProvider->pagination->setPageSize($limit);
        $dataProvider->pagination->setPage($offset);

        //Fetch news and parse it
        $items = $dataProvider->getModels();

        //Resulting array of items
        $itemsArray = [];

        foreach ($items as $item) {
            $newItem = self::parseItem($item);

            if (!empty($newItem)) {
                //Insert New Item
                $itemsArray[] = $newItem;
            }
        }

        return $itemsArray;
    }

    /**
     * Obtain a single news
     * @param $bodyParams
     * @return array
     */
    public static function getItem($bodyParams)
    {
        //Id of the record
        $identifier = $bodyParams['id'];

        //Fetch news and parse it
        $item = News::findOne($identifier);

        //Resulting array of items
        $itemsArray = [];

        return [self::parseItem($item)];
    }

    /**
     * Parse single news and return an api designed array
     * @param $item
     * @return array
     */
    public static function parseItem($item)
    {
        //The base class name
        $baseClassName = StringHelper::basename(News2::className());

        //Read permission name
        $readPremission = strtoupper($baseClassName . '_READ');

        //Edit permission name
        $editPremission = strtoupper($baseClassName . '_UPDATE');

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

            //Fill fields from item usable in app
            $newItem['fields'] = [
                'slug' => $item->slug,
                'titolo' => self::flushHtml($item->titolo),
                'sottotitolo' => self::flushHtml($item->sottotitolo),
                'descrizione_breve' => self::flushHtml($item->descrizione_breve),
                'descrizione' => self::flushHtml($item->descrizione),
                'data_pubblicazione' => $item->data_pubblicazione,
                'created_at' => $item->created_at,
                'created_by' => $item->created_by,
                'comments_enabled' => $item->comments_enabled,
                'owner' => [
                    'nome' => $owner->nome,
                    'cognome' => $owner->cognome,
                    'presentazione_breve' => $owner->presentazione_breve,
                    'avatarUrl' => $owner->avatarWebUrl,
                ],
                'newsImageUrl' => $image ? Yii::$app->getUrlManager()->createAbsoluteUrl($image->getWebUrl()) : null,
            ];
            $url = '';
            if (self::isContentShared($item)) {
                $view_url = $item->getViewUrl();
                $url = substr($view_url, 0, strrpos($view_url, "/")) . '/public' . "?id=" . $item->id;
            }
            $newItem['shareUrl'] = $url;
            $newItem['likeMe'] = self::isLikeMe($item);
            $newItem['countLikeMe'] = self::getCountLike($item);
            //Remove id as is not needed
            unset($newItem['fields']['id']);

            //Can edit
            $newItem['canEdit'] = Yii::$app->user->can($editPremission, ['model' => $item]);

            return $newItem;
        }

        return [];
    }

    /**
     *
     * @param type $model
     * @return boolean
     */
    private static function isContentShared($model)
    {
        $obj = $model;
        if ($obj) {
            $classname = get_class($obj);
            $contentShared = ContentShared::find()
                ->innerJoinWith('modelsClassname')
                ->andWhere(['classname' => $classname, 'content_id' => $obj->id])->one();

            if ($contentShared) {
                return true;
            }
        }

        return false;
    }
}
