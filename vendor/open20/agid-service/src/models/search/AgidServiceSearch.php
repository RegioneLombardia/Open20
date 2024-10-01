<?php

namespace open20\agid\service\models\search;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use open20\agid\service\models\AgidService;
use open20\amos\core\record\CmsField;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\agid\service\models\AgidServiceRelatedServiceMm;
use open20\agid\service\models\AgidServiceOrganizationalUnitMm;

/**
 * AgidServiceSearch represents the model behind the search form about `open20\agid\service\models\AgidService`.
 */
class AgidServiceSearch extends AgidService implements CmsModelInterface
{
    public $isSearch;

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'agid_content_type_service_id', 'agid_service_type_id', 'agid_service_status_id', 'agid_uo_manager_id', 'agid_uo_area_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'service_status_motivation', 'subtitle', 'description', 'long_description', 'recipients_description', 'persons_apply', 'geographical_apply', 'procedure_apply', 'output', 'outcome_procedure_apply', 'digital_channel_url', 'authentication_way', 'physical_channel', 'physical_channel_reservation', 'instructions', 'costs', 'constrains', 'phases_deadline', 'special_case', 'external_links', 'status', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['AgidServiceContentType', 'safe'],
            ['AgidServiceStatus', 'safe'],
            ['AgidServiceType', 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $queryType = NULL, $limit = NULL, $onlyDrafts = false, $pageSize = NULL)
    {
        $query = AgidService::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('agidServiceContentType');
        $query->joinWith('agidServiceStatus');
        $query->joinWith('agidServiceType');
        $query->joinWith('agidUoManager');

        $query->joinWith('agidServiceRelatedServiceMm');

        $query->distinct()->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '".  str_replace('\\','\\\\',AgidService::className()) . "' and ".EntitysTagsMm::tableName(). ".record_id = ". AgidService::tableName() . ".id and " . EntitysTagsMm::tableName(). ".deleted_at is NULL");  

        $dataProvider->setSort([
            'attributes' => [
                'agidServiceType' => [
                    'asc' => ['agid_service_type.name' => SORT_ASC],
                    'desc' => ['agid_service_type.name' => SORT_DESC],
                ],
                'name' => [
                    'asc' => ['agid_service.name' => SORT_ASC],
                    'desc' => ['agid_service.name' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            AgidService::tableName() . '.id' => $this->id,
            AgidService::tableName() . '.agid_content_type_service_id' => $this->agid_content_type_service_id,
            AgidService::tableName() . '.agid_service_type_id' => $this->agid_service_type_id,
            AgidService::tableName() . '.agid_service_status_id' => $this->agid_service_status_id,
            // AgidService::tableName() . '.agid_uo_manager_id' => $this->agid_uo_manager_id,
            AgidService::tableName() . '.agid_uo_area_id' => $this->agid_uo_area_id,
            AgidService::tableName() . '.created_at' => $this->created_at,
            AgidService::tableName() . '.updated_at' => $this->updated_at,
            AgidService::tableName() . '.deleted_at' => $this->deleted_at,
            AgidService::tableName() . '.created_by' => $this->created_by,
            AgidService::tableName() . '.updated_by' => $this->updated_by,
            AgidService::tableName() . '.deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', AgidService::tableName() . '.name', $this->name])
            ->andFilterWhere(['like', AgidService::tableName() . '.service_status_motivation', $this->service_status_motivation])
            ->andFilterWhere(['like', AgidService::tableName() . '.subtitle', $this->subtitle])
            ->andFilterWhere(['like', AgidService::tableName() . '.description', $this->description])
            ->andFilterWhere(['like', AgidService::tableName() . '.long_description', $this->long_description])
            ->andFilterWhere(['like', AgidService::tableName() . '.recipients_description', $this->recipients_description])
            ->andFilterWhere(['like', AgidService::tableName() . '.further_information', $this->further_information])
            ->andFilterWhere(['like', AgidService::tableName() . '.persons_apply', $this->persons_apply])
            ->andFilterWhere(['like', AgidService::tableName() . '.geographical_apply', $this->geographical_apply])
            ->andFilterWhere(['like', AgidService::tableName() . '.procedure_apply', $this->procedure_apply])
            ->andFilterWhere(['like', AgidService::tableName() . '.output', $this->output])
            ->andFilterWhere(['like', AgidService::tableName() . '.outcome_procedure_apply', $this->outcome_procedure_apply])
            ->andFilterWhere(['like', AgidService::tableName() . '.digital_channel_url', $this->digital_channel_url])
            ->andFilterWhere(['like', AgidService::tableName() . '.authentication_way', $this->authentication_way])
            ->andFilterWhere(['like', AgidService::tableName() . '.physical_channel', $this->physical_channel])
            ->andFilterWhere(['like', AgidService::tableName() . '.physical_channel_reservation', $this->physical_channel_reservation])
            ->andFilterWhere(['like', AgidService::tableName() . '.instructions', $this->instructions])
            ->andFilterWhere(['like', AgidService::tableName() . '.costs', $this->costs])
            ->andFilterWhere(['like', AgidService::tableName() . '.constrains', $this->constrains])
            ->andFilterWhere(['like', AgidService::tableName() . '.phases_deadline', $this->phases_deadline])
            ->andFilterWhere(['like', AgidService::tableName() . '.special_case', $this->special_case])
            ->andFilterWhere(['like', AgidService::tableName() . '.external_links', $this->external_links])
            ->andFilterWhere(['like', AgidService::tableName() . '.status', $this->status]);


        // UPDATE FROM / TO 
        $class_name = end(explode("\\", $this::className()));

        if( !empty($params[$class_name]['updated_from']) ){

            $query->andWhere(['>=', AgidService::tableName() . '.updated_at', $params[$class_name]['updated_from'] ]);
        }

        if( !empty($params[$class_name]['updated_to']) ){

            $query->andWhere(['<=', AgidService::tableName() . '.updated_at', $params[$class_name]['updated_to'] ]);
        }

        $dataProvider = $this->filterByAgidServiceRelated($params, $dataProvider);
        $dataProvider = $this->filterByOrganizationalUnitRelated($params, $dataProvider);

        return $dataProvider;
    }


    /**
     *  Method for filtering related AgidOrganizationalUnit, for agid_service_organizational_unit_mm  
     *
     * @param array | $params
     * @param [type] $dataProvider
     * @return dataProvider
     */
    public function filterByOrganizationalUnitRelated($params, $dataProvider){

        if( isset(\Yii::$app->request->get('AgidServiceSearch')['agid_uo_manager_id']) ){

            if( \is_numeric(\Yii::$app->request->get('AgidServiceSearch')['agid_uo_manager_id']) ){

                // estrazione dei servizi che hanno la corrispettiva uo associata
                $agid_service_id = ArrayHelper::getColumn(
                    AgidServiceOrganizationalUnitMm::find()
                        ->andWhere(['agid_organizational_unit_id' => \Yii::$app->request->get('AgidServiceSearch')['agid_uo_manager_id']])
                        ->andWhere(['deleted_at' => null])
                        ->all(),

                    function ($element) {
                        return $element['agid_service_id'];
                    }
                );

                // filtro per gli agid_service_id
                $query = $dataProvider->query->andWhere(['agid_service.id' => $agid_service_id ])
                                                ->andWhere(['agid_service.deleted_at' => null]);

                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                ]);
            }
        }
        
        return $dataProvider;
    }

    /**
     * Method for filtering related services, for agid_service_related_service_mm  
     *
     * @param array $params
     * @param ActiveDataProvider $dataProvider
     * 
     * @return ActiveDataProvider $dataProvider
     */
    public function filterByAgidServiceRelated($params, $dataProvider){

        if( isset($params['AgidServiceSearch']['agid_service_related_service_mm']) ){

            if( \is_numeric($params['AgidServiceSearch']['agid_service_related_service_mm']) ){

                // estrazione agid_service_related_service_mm ove agid_related_service_id corrisponde al parametro agid_service_related_service_mm
                $agid_service_related_service_mm = AgidServiceRelatedServiceMm::find()
                                                    ->andWhere([
                                                        'agid_related_service_id' => $params['AgidServiceSearch']['agid_service_related_service_mm']
                                                    ])
                                                    ->andWhere(['deleted_at' => null])
                                                    ->all();

                $agid_service_id = ArrayHelper::getColumn(  $agid_service_related_service_mm,
                                                            function ($element) {
                                                                return $element['agid_service_id'];
                                                            }
                                                        );

                // filtro per gli agid_service_id
                $query = $dataProvider->query->andWhere(['agid_service.id' => $agid_service_id ])
                                                ->andWhere(['agid_service.deleted_at' => null]);

                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                ]);
            }
        }

        return $dataProvider;
    }

    public function cmsIsVisible($id) 
    {
        $retValue = true;
        return $retValue;
    }

    public function cmsSearch($params, $limit) 
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $dataProvider  = $this->search($params);
        $query = $dataProvider->query;
        $i=0;
        foreach ($this->agid_service_type_id as $id) {
            if ($i == 0) {
                $query->andFilterWhere(['like', 'agid_service_type_id', $id]);
            } else {
                $query->orFilterWhere(['like', 'agid_service_type_id', $id]);
            }
            $i++;
        }
        if ($params["withPagination"]) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }
        $query->andWhere([AgidService::tableName().'.status' => AgidService::AGID_SERVICE_STATUS_VALIDATED,]);
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return ".$command.";"));
            }
        }


        /**
         * cms panel
         * Condizione di ordinamento
         * updated_at => DESC, name => DESC
         */
        if( isset($params['orderBy']) && $params['orderBy'] ){

            $list_fields = explode(",", $params['orderBy']);

            foreach ($list_fields as $key => $field_order) {
                
                $arr_field_order = explode("=>", $field_order);
                $field = trim($arr_field_order[0]);
                $order = trim($arr_field_order[1]);

                $query->addOrderBy("$field $order");
            }

        }else{
            // default order
            $query->orderBy(['name' => ASC]);
        }

        return $dataProvider;
    }

    public function cmsSearchFields()
    {
        $searchFields = [];

        array_push($searchFields, new CmsField("name", "TEXT"));
        array_push($searchFields, new CmsField("subtitle", "TEXT"));
        array_push($searchFields, new CmsField("description", "TEXT"));
        array_push($searchFields, new CmsField("long_description", "TEXT"));
        array_push($searchFields, new CmsField("recipients_description", "TEXT"));

        return $searchFields;
    }

    public function cmsViewFields()
    {
        return [
            new CmsField('name', 'TEXT', 'amosservice', $this->attributeLabels()['name']),
            new CmsField('subtitle', 'TEXT', 'amosservice', $this->attributeLabels()['subtitle']),
            new CmsField('description', 'TEXT', 'amosservice', $this->attributeLabels()['description']),
            new CmsField('long_description', 'TEXT', 'amosservice', $this->attributeLabels()['long_description']),
            new CmsField('recipients_description', 'TEXT', 'amosservice', $this->attributeLabels()['recipients_description']),
        ];
    }

}
