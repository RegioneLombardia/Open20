<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\basic
 * @category   CategoryName
 */

namespace open20\amos\myactivities\basic;

use open20\amos\myactivities\models\search\MyActivitiesModelSearch;
use yii\helpers\ArrayHelper;

/**
 * Class MyActivitiesList
 * @package open20\amos\myactivities\basic
 */
class MyActivitiesList implements MyActivitiesListInterface
{
    /** @var array $myActivitiesList */
    private $myActivitiesList;

    /**
     * @return array
     */
    public function getMyActivitiesList()
    {
        return $this->myActivitiesList;
    }

    /**
     * @param array $myActivitiesList
     */
    public function setMyActivitiesList($myActivitiesList)
    {
        $this->myActivitiesList = $myActivitiesList;
    }

    /**
     * @param array $listModel
     */
    public function addModelSet($listModel)
    {
        if (!is_null($this->myActivitiesList)) {
            $this->myActivitiesList = ArrayHelper::merge($this->myActivitiesList, $listModel);
        } else {
            $this->myActivitiesList = array_merge($listModel);
        }
    }

    /**
     * @param MyActivitiesModelSearch $modelSearch
     */
    public function applyFilter($modelSearch)
    {
        if (!is_null($modelSearch) && !empty($modelSearch)) {
            if (!empty($this->myActivitiesList)) {
                /** @var MyActivitiesModelsInterface $activity */
                foreach ($this->myActivitiesList as $key => $activity) {
                    // filter for SearchString
                    $searchString = $activity->getSearchString();
                    $modelSearchString = $modelSearch->searchString;
                    if (!empty($searchString) && !empty($modelSearchString)) {
                        if (strpos(strtoupper($activity->getSearchString()), strtoupper($modelSearch->searchString)) === false) {
                            unset($this->myActivitiesList[$key]);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param MyActivitiesModelSearch $modelSearch
     */
    public function applySort($modelSearch)
    {
        if (!is_null($modelSearch) && !empty($modelSearch)) {
            $sortType = $modelSearch->orderType;
            if (!empty($sortType)) {
                $this->sortMyActivitiesList($sortType);
                return;
            }
        }
        $myActivitiesModule = \Yii::$app->getModule('myactivities');
        $this->sortMyActivitiesList($myActivitiesModule->orderType);
    }

    /**
     * @param int $mode = SORT_DESC | SORT_ASC
     */
    public function sortMyActivitiesList($mode = SORT_ASC)
    {
        if (count($this->myActivitiesList) > 0) {
            if ($mode == SORT_DESC) {
                ArrayHelper::multisort($this->myActivitiesList, 'updatedAt', SORT_DESC);
            } else {
                ArrayHelper::multisort($this->myActivitiesList, 'updatedAt', SORT_ASC);
            }
        }
    }
}
