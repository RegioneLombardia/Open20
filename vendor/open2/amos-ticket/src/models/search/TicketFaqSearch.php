<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\models\search
 * @category   CategoryName
 */

namespace open2\amos\ticket\models\search;

use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\cwh\AmosCwh;
use open2\amos\ticket\models\TicketFaq;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class TicketFaqSearch
 * TicketFaqSearch represents the model behind the search form about `open2\amos\ticket\models\TicketFaq`.
 * @package open2\amos\ticket\models\search
 */
class TicketFaqSearch extends TicketFaq implements CmsModelInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ticket_categoria_id', 'version', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['domanda', 'risposta', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    public function getScope($params)
    {
        $scope = $this->formName();
        if (!isset($params[$scope])) {
            $scope = '';
        }
        return $scope;
    }
    
    public function search($params, $limit = null)
    {
        /** @var ActiveQuery $query */
        $query = TicketFaq::find();
        
        $query->joinWith('ticketCategoria');
        
        $abilita_per_community = false;
        
        // If scope set, filter categories for cwh
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (!is_null($moduleCwh)) {
            /** @var AmosCwh $moduleCwh */
            $scope = $moduleCwh->getCwhScope();
            if (!empty($scope) && isset($scope['community'])) {
                $abilita_per_community = true;
                $query->andFilterWhere([
                    'community_id' => $scope['community'],
                ]);
            }
        }
        
        $query->andFilterWhere([
            'ticket_categorie.abilita_per_community' => $abilita_per_community,
        ]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $scope = $this->getScope($params);
        
        if (!($this->load($params, $scope) && $this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'ticket_categoria_id' => $this->ticket_categoria_id,
            'version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);
        
        $query->andFilterWhere(['like', 'domanda', $this->domanda])
            ->andFilterWhere(['like', 'risposta', $this->risposta]);
        if ($params["withPagination"]) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return " . $command . ";"));
            }
        }
        return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param int|null $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchFrontEnd($params, $limit = null)
    {
        /** @var ActiveQuery $query */
        $query = TicketFaq::find();
        
        $query->joinWith('ticketCategoria');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $query->andFilterWhere([
            'id' => $this->id,
            'ticket_categoria_id' => $this->ticket_categoria_id,
            TicketFaq::tableName() . '.version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);
        
        $query->andFilterWhere(['like', 'domanda', $this->domanda])
            ->andFilterWhere(['like', 'risposta', $this->risposta]);
        if ($params["withPagination"]) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return " . $command . ";"));
            }
        }
        return $dataProvider;
    }
    
    /**
     * @inheritdoc
     */
    public function cmsSearch($params, $limit)
    {
        $params = array_merge($params, \Yii::$app->request->get());
        return $this->searchFrontEnd($params, $limit);
    }
    
    /**
     * @inheritdoc
     */
    public function cmsViewFields()
    {
        $viewFields = [];
        array_push($viewFields, new CmsField("domanda", "TEXT", 'amosticket', $this->attributeLabels()["domanda"]));
        array_push($viewFields, new CmsField("risposta", "TEXT", 'amosticket', $this->attributeLabels()['risposta']));
        return $viewFields;
    }
    
    /**
     * @inheritdoc
     */
    public function cmsSearchFields()
    {
        $searchFields = [];
        array_push($searchFields, new CmsField("domanda", "TEXT"));
        array_push($searchFields, new CmsField("risposta", "TEXT"));
        return $searchFields;
    }
    
    /**
     * @inheritdoc
     */
    public function cmsIsVisible($id)
    {
        return true;
    }
}
