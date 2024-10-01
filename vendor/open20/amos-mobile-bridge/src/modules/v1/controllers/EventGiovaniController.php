<?php

namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\StringHelper;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\documenti\models\Documenti;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventParticipantCompanion;
use open20\amos\events\models\search\EventSearch;
use open20\amos\events\utility\EventsUtility;
use open20\amos\mobile\bridge\modules\v1\utility\EventUtility;
use open20\amos\news\models\News;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\tag\models\Tag;
use Exception;
use kartik\mpdf\Pdf;
use Yii;
use yii\db\Expression;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\log\Logger;
use yii\rest\Controller;

class EventGiovaniController extends EventApiBaseController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();

        $behaviours = ArrayHelper::merge($behaviours,
            [
                'verbFilter' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'events-list' => ['get']
                    ],
                ],
            ]);
        $behaviours['pageCache'] = EventUtility::mobileCacheConfigs();
        return $behaviours;
    }

    /**
     * @param null $pageSize
     * @param null $pageNumber
     * @param int $category
     * @param null $title
     * @param null $dateFrom
     * @param null $dateTo
     * @return array
     */
    public function actionEventsList($pageSize = null, $pageNumber = null, $category = 0, $title = null, $dateFrom = null, $dateTo = null)
    {
        $list = [];
//        try {

        // Base list
        $dataProvider = EventApiBaseController::getBaseEventsList()['dataProvider'];
        $query = EventApiBaseController::getBaseEventsList()['query'];

        // Search only events to publish on Giovani Platform
        $query->andWhere(['publish_on_portale_giovani' => 1]);

        // Category
        if ($category != 0) {
            $query->andWhere(['portale_giovani_category' => $category]);
        }

        // Title
        if (!empty($title)) {
            $query->andWhere(['like', 'event.title', $title]);
        }

        // Period between events are in progress (active)
        // Date from
        if (!empty($dateFrom)) {
            $query->andWhere(['or',
                ['>=', 'begin_date_hour', $dateFrom],
                ['>=', 'end_date_hour', $dateFrom]
            ]);
        }

        // Date to
        if (!empty($dateTo)) {
            $query->andWhere(['or',
                ['<=', 'begin_date_hour', $dateTo],
                ['<=', 'end_date_hour', $dateTo]
            ]);
        }

        // Order
        $dataProvider->sort = ['defaultOrder' => ['begin_date_hour' => SORT_DESC]];

        // Add query to dataProvider
        $dataProvider->query = $query;

        // Add pagination to dataProvider
        if (is_null($pageSize)) {
            $dataProvider->pagination = false;
        } else {
            $dataProvider->pagination->pageSize = $pageSize;
            $dataProvider->pagination->page = $pageNumber;
        }

        // Api Pagination
        $totalRowCount = $query->count();
        $pagination = [
            'totalRowCount' => intval($totalRowCount),
            'pageSize' => intval($pageSize),
            'pageNumber' => intval($pageNumber)
        ];

        // Items list
        $listModel = $dataProvider->getModels();
        foreach ($listModel as $model) {
            $item = EventApiBaseController::baseParseItem($model);
            // Add poi_category field
            $item['fields']['portale_giovani_category'] = [
                'id' => $model->portale_giovani_category,
                'label' => Event::getGiovaniPlatformCategoryLabel()[$model->portale_giovani_category]
            ];

            $list[] = $item;
        }

        // Number of elements in current page
        $eventsInPage = EventApiBaseController::getCurrentPageNumberOfElements($list);
        $pagination['eventsInPage'] = $eventsInPage;

//        } catch (Exception $ex) {
//            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
//        }

        return array_merge($pagination, $list);
    }

}