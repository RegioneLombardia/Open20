<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\rss\controllers
 * @category   CategoryName
 */

namespace amos\rss\controllers;

use amos\rss\utility\FeedUtility;
use Yii;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class RssController
 * @package amos\rss\controllers
 */
class RssController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'rss',
                            'rss-feed'
                        ],
                        'roles' => ['?', '@']
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionRss()
    {
        $out = '';

        try {
            $feedUtil = new FeedUtility();
            $feed = $feedUtil->createFeedWriter();
            /**
             * Add one or more entries. Note that entries must
             * be manually added once created.
             */
            $this->buildRss($feed);
            /**
             * Render the resulting feed to Atom 1.0 and assign to $out.
             * You can substitute "atom" with "rss" to generate an RSS 2.0 feed.
             */
            $out = $feed->export('rss');
        } catch (\Zend\Feed\Writer\Exception\RuntimeException $zex) {
            Yii::getLogger()->log($zex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }

        header('Content-type: text/xml');

        return $out;
    }

    /**
     * @param int $id
     * @param $type
     * @return string
     */
    public function actionRssFeed($id, $type)
    {
        $out = '';
        try {
            $feedUtil = new FeedUtility();
            $feed = $feedUtil->createFeedWriter();

            $model = $this->module->modelsEnabled[$type];
            $modelObj = $model::findOne(['id' => $id]);
            if (!is_null($modelObj)) {
                $feedUtil->addEntryInFeed($feed, $modelObj, $type);
            }
            $out = $feed->export('rss');
        } catch (\Zend\Feed\Writer\Exception\RuntimeException $zex) {
            Yii::getLogger()->log($zex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
        header('Content-type: text/xml');
        return $out;
    }

    /**
     * @param \Zend\Feed\Writer\Feed $feed
     */
    private function buildRss(\Zend\Feed\Writer\Feed $feed)
    {
        $feedUtil = new FeedUtility();

        foreach ($this->module->modelsEnabled as $key => $model) {
            $entities = $this->loadModels($model);
            foreach ($entities as $entity) {
                $feedUtil->addEntryInFeed($feed, $entity, $key);
            }
        }
    }

    /**
     * @param string $model
     * @return array
     */
    private function loadModels($model)
    {
        $entities = [];
        $cwh = Yii::$app->getModule('cwh');

        try {
            $modelObj = \Yii::createObject($model);
            if (!is_null($cwh)) {
                $modelObj = \Yii::createObject($model);
                $configContent = \open20\amos\cwh\models\CwhConfigContents::findOne(['tablename' => $modelObj->tableName()]);
                if (!is_null($configContent)) {
                    $entities = $modelObj->find()
                        ->innerJoin('cwh_pubblicazioni', 'cwh_config_contents_id = ' . $configContent->id . ' and cwh_pubblicazioni.content_id = ' . $modelObj->tableName()
                            . '.id and cwh_regole_pubblicazione_id = 1')
                        ->andWhere(['status' => $modelObj->getValidatedStatus()])
                        ->limit($this->module->limitRss)->orderBy([
                            'created_at' => SORT_DESC
                        ])->all();
                } else {
                    $cwhConfig = \open20\amos\cwh\models\CwhConfig::findOne(['tablename' => $modelObj->tableName()]);
                    if (!is_null($cwhConfig)) {
                        $params = [];
                        /** @var ActiveQuery $query */
                        $query = $modelObj->baseSearch($params);
                        if($modelObj::getVisibility() != '1') {
                            $modelObj->getVisibleNetworksQuery($query);
                        }
                        $modelObj->filterValidated($query);
                        $entities = $query->limit($this->module->limitRss)->orderBy([
                            'created_at' => SORT_DESC
                        ])->all();
                    }
                }
            } else {
                $entities = $modelObj->find()
                    ->andWhere(['status' => $modelObj->getValidatedStatus()])
                    ->limit($this->module->limitRss)->orderBy([
                        'created_at' => SORT_DESC
                    ])->all();
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }

        return $entities;
    }
}
