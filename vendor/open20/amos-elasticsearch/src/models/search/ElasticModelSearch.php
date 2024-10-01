<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace open20\elasticsearch\models\search;

use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\cwh\utility\CwhUtil;
use open20\elasticsearch\base\ElasticDataProvider;
use open20\elasticsearch\models\ElasticModel;
use open20\elasticsearch\models\ElasticQuery;
use open20\elasticsearch\Module;
use open20\elasticsearch\transformer\AbstractTransformer;
use Yii;

class ElasticModelSearch extends ElasticModel implements CmsModelInterface {

    private $module;
    
    public function getModule() {
        return $this->module;
    }

        
    public function init() {
        parent::init();
        $this->module = Module::getInstance();
    }

    public function cmsIsVisible($id) {
        $retValue = true;
        return $retValue;
    }

    public function cmsSearch($params, $limit) {
        $filter = '';

        $params = array_merge($params, Yii::$app->request->get());
        $query = new ElasticQuery();
        $query->boolQuery();
        $query = $this->addCwhConditions($query);
        if (!empty($params['searchtext'])) {
            $query->addMust("query", "*" . $this->getModule()->folding->folding($params['searchtext']), "query_string", "open20_italian", ["title^5", "*^1"]);
        }

        $dataProvider = new ElasticDataProvider([
            'query' => $query,
        ]);
        if ($params["withPagination"]) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }
        return $dataProvider;
    }

    public function cmsSearchFields() {
        $searchFields = [];

        array_push($searchFields, new CmsField("title", "TEXT"));
        array_push($searchFields, new CmsField("description", "TEXT"));

        return $searchFields;
    }

    public function cmsViewFields() {
        return [
            new CmsField('title', 'TEXT', 'amoselasticsearch', $this->attributeLabels()['title']),
            new CmsField('description', 'TEXT', 'amoselasticsearch', $this->attributeLabels()['description']),
        ];
    }

    /**
     * 
     * @param type $query
     * @return type
     */
    public function addCwhConditions($query) {
        if ($this->getModule()->enableCwh) {
            $loggedUser = Yii::$app->user->identity;
            $loggedUserId = $loggedUser->id;
            $loggedUserProfileId = $loggedUser->userProfile->id;
            
            if ($this->getModule()->filterCwhByUserTags) {
                $usersTags = $this->getUsersTag($loggedUserProfileId);
                $usersTags[] = sprintf("'%s'",AbstractTransformer::NOTAGS_TAG);
                $query->addShould(AbstractTransformer::TAGS_INDEX, $usersTags);
            }
            $cwhModule = \Yii::$app->getModule('cwh');
            if ($cwhModule) {
                $listNetworks = $cwhModule->getUserNetworks($loggedUserId);
                $networksIds = [];
                $networksIds[] = AbstractTransformer::PLATFORM_TAG;
                if (count($listNetworks)) {
                    foreach($listNetworks as $record){
                        $networksIds[] = str_replace('-', '', $record['id']);
                    }
                }
                $query->addAsBoolFilter(AbstractTransformer::NETWORKS_INDEX, $networksIds, 'match');
            }
        }
        return $query;
    }

    /**
     * 
     * @param type $userProfileId
     * @return type
     */
    private function getUsersTag($userProfileId) {
        $usersTags = [];
        if (!is_null(Yii::$app->getModule('cwh'))) {
            $usersTags = CwhUtil::findInterestTagIdsByUser($userProfileId);
            $calcTags = [];
            foreach($usersTags as $tag){
               $calcTags[] =  "'" . $tag . "'";
            }
            $usersTags = $calcTags;
        }
        return $usersTags;
    }

}
