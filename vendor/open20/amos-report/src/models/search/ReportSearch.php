<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report\models\search
 * @category   Model
 */

namespace open20\amos\report\models\search;

use open20\amos\report\models\Report;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\models\NotificationChannels;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\log\Logger;

/**
 * ReportSearch represents the model behind the search form about `open20\amos\report\models\Report`.
 */
class ReportSearch extends Report
{
    /**
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Basic search of news. Returns all the news and not canceled.
     *
     * @param   array $params Parametri
     * @return \yii\db\ActiveQuery
     */
    public function baseSearch($params)
    {
        //init the default search values
        $this->initOrderVars();

        //check params to get orders value
        $this->setOrderVars($params);

        return Report::find()->distinct();
    }

    /**
     * Search method useful for retrieve reports.
     *
     * @param $params
     * @param null $limit
     * @param null $type
     * @param bool $only_drafts
     * @return ActiveDataProvider
     */
    public function search($params, $limit = null, $type = null)
    {
        $query = $this->buildQuery($params,$type);
        $query->limit($limit);

        /** @var  $notify AmosNotify*/
        $notify = Yii::$app->getModule('notify');
        if($notify)
        {
            $notify->notificationOff(Yii::$app->getUser()->id, Report::className(),$query,NotificationChannels::CHANNEL_READ);
        }

        //set the data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $limit,
            ]
        ]);
        //check if can use the custom module order
        if($this->canUseModuleOrder()){
            $dataProvider->setSort([
                'defaultOrder' => [
                    $this->orderAttribute => (int)$this->orderType
                ]
            ]);
        }else{ //for widget graphic last news, order is incorrect without this else
            $dataProvider->setSort([
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC
                ]
            ]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by
        ]);

        $query->andFilterWhere(['like', 'content', $this->content]);


        return $dataProvider;
    }

    /**
     * @param $params
     * @param $type
     * @param bool $only_drafts
     * @param null $limit
     * @return ActiveQuery
     */
    public function buildQuery($params, $type){
        try{
            //common parameters
            $dataOdierna = date('Y-m-d');
            $userProfileId = Yii::$app->getUser()->id;

            /** @var ActiveQuery $query */
            $query = $this->baseSearch($params);

            /** @var string $classname News className to check cwh*/
            $classname = Report::className();

        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $query;
    }


    /**
     * Search method useful for retrieve all non-deleted news.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchAll($params, $limit = null)
    {
        return $this->search($params, $limit, "all");
    }

}
