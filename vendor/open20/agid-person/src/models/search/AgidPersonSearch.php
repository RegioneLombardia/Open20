<?php

namespace open20\agid\person\models\search;


use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\ContentModelSearchInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\CmsField;
use open20\agid\person\models\AgidPerson;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\tag\models\EntitysTagsMm;

/**
 * AgidPersonSearch represents the model behind the search form about `backend\modules\operators\models\AgidPerson`.
 */
class AgidPersonSearch extends AgidPerson implements  CmsModelInterface, SearchModelInterface, ContentModelSearchInterface
{
    public $isSearch;

//private $container; 

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'agid_person_content_type_id', 'agid_person_type_id', 'agid_document_cv_id', 'agid_document_import_id', 'agid_document_other_posts_id', 'agid_document_nomination_id', 'agid_document_balance_sheet_id', 'agid_document_tax_return_id', 'agid_document_election_expenses_id', 'agid_document_changes_balance_sheet_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'surname', 'role', 'role_description', 'date_end_assignment', 'skills', 'delegation', 'date_start_settlement', 'bio', 'telephone', 'email', 'other_info', 'status', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['AgidPersonContentType', 'safe'],
            ['AgidPersonType', 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $queryType = NULL, $limit = NULL, $onlyDrafts = false, $pageSize = NULL)
    {
        $query = AgidPerson::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('agidPersonContentType');
        $query->joinWith('agidPersonType');
        $query->distinct()->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '".  str_replace('\\','\\\\',AgidPerson::className()) . "' and ".EntitysTagsMm::tableName(). ".record_id = ". AgidPerson::tableName() . ".id and " . EntitysTagsMm::tableName(). ".deleted_at is NULL");  

        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => ['agid_person.name' => SORT_ASC],
                    'desc' => ['agid_person.name' => SORT_DESC],
                ],
                'surname' => [
                    'asc' => ['agid_person.surname' => SORT_ASC],
                    'desc' => ['agid_person.surname' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'agid_person.agid_person_content_type_id' => $this->agid_person_content_type_id,
            'agid_person.agid_person_type_id' => $this->agid_person_type_id,
            'agid_person.agid_document_cv_id' => $this->agid_document_cv_id,
            'agid_person.agid_document_import_id' => $this->agid_document_import_id,
            'agid_person.agid_document_other_posts_id' => $this->agid_document_other_posts_id,
            'agid_person.agid_document_nomination_id' => $this->agid_document_nomination_id,
            'agid_person.agid_document_balance_sheet_id' => $this->agid_document_balance_sheet_id,
            'agid_person.agid_document_tax_return_id' => $this->agid_document_tax_return_id,
            'agid_person.agid_document_election_expenses_id' => $this->agid_document_election_expenses_id,
            'agid_person.agid_document_changes_balance_sheet_id' => $this->agid_document_changes_balance_sheet_id,
            'agid_person.date_end_assignment' => $this->date_end_assignment,
            'agid_person.date_start_settlement' => $this->date_start_settlement,
            'agid_person.created_at' => $this->created_at,
            'agid_person.updated_at' => $this->updated_at,
            'agid_person.deleted_at' => $this->deleted_at,
            'agid_person.created_by' => $this->created_by,
            'agid_person.updated_by' => $this->updated_by,
            'agid_person.deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'agid_person.name', $this->name])
            ->andFilterWhere(['like', 'agid_person.surname', $this->surname])
            ->andFilterWhere(['like', 'agid_person.role', $this->role])
            ->andFilterWhere(['like', 'agid_person.role_description', $this->role_description])
            ->andFilterWhere(['like', 'agid_person.skills', $this->skills])
            ->andFilterWhere(['like', 'agid_person.delegation', $this->delegation])
            ->andFilterWhere(['like', 'agid_person.bio', $this->bio])
            ->andFilterWhere(['like', 'agid_person.telephone', $this->telephone])
            ->andFilterWhere(['like', 'agid_person.email', $this->email])
            ->andFilterWhere(['like', 'agid_person.other_info', $this->other_info])
            ->andFilterWhere(['like', 'agid_person.status', $this->status]);

            
            // UPDATE FROM / TO 
            $class_name = end(explode("\\", $this::className()));

            if( !empty($params[$class_name]['updated_from']) ){

                $query->andWhere(['>=', 'agid_person.updated_at', $params[$class_name]['updated_from'] ]);
            }

            if( !empty($params[$class_name]['updated_to']) ){

                $query->andWhere(['<=', 'agid_person.updated_at', $params[$class_name]['updated_to'] ]);
            }

            // ORGANIZATIONAL UNIT OF REFERENCE
            if( !empty($params[$class_name]['organizational_unit_of_reference']) ){

                $query->andWhere([ 'or',
                            ['agid_organizational_unit_1_id' => $params[$class_name]['organizational_unit_of_reference']],
                            ['agid_organizational_unit_2_id' => $params[$class_name]['organizational_unit_of_reference']],
                            ['agid_organizational_unit_3_id' => $params[$class_name]['organizational_unit_of_reference']],
                            ['agid_organizational_unit_4_id' => $params[$class_name]['organizational_unit_of_reference']],
                            ['agid_organizational_unit_5_id' => $params[$class_name]['organizational_unit_of_reference']],
                        ]);
            }

            $dataProvider = $this->assignmentCompleted($params, $dataProvider);

        return $dataProvider;
    }

    /**
     * Method to filter dataProvider of person by date_end_assignment
     * 
     * search form field "Incarico concluso (si/no)"
     *
     * @param array $params
     * @param [dataProvider] $dataProvider
     * @return $dataProvider
     */
    public function assignmentCompleted($params, $dataProvider){

        if( isset($params['AgidPersonSearch']['date_end_assignment']) ){

            if( !empty($params['AgidPersonSearch']['date_end_assignment']) ){

                if( $params['AgidPersonSearch']['date_end_assignment'] == "si" ){

                    $query = $dataProvider->query;
                    $query = $query->where(['IS NOT', 'date_end_assignment', null]);

                    $dataProvider = new ActiveDataProvider([
                                            'query' => $query,
                                        ]);

                }elseif ( $params['AgidPersonSearch']['date_end_assignment'] == 'no' ) {
                    
                    $query = $dataProvider->query;
                    $query = $query->where(['IS','date_end_assignment', null]);

                    $dataProvider = new ActiveDataProvider([
                                                'query' => $query,
                                            ]);
                }
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
        foreach ($this->agid_person_type_id as $id) {
            if ($i == 0) {
                $query->andFilterWhere(['like', 'agid_person_type_id', $id]);
            } else {
                $query->orFilterWhere(['like', 'agid_person_type_id', $id]);
            }
            $i++;
        }
        if ($params["withPagination"]) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }
        $query->andWhere([AgidPerson::tableName().'.status' => AgidPerson::AGID_PERSON_STATUS_VALIDATED,]);
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return ".$command.";"));
            }
        }
        return $dataProvider;
    }

    public function cmsSearchFields()
    {
        $searchFields = [];

        array_push($searchFields, new CmsField("name", "TEXT"));
        array_push($searchFields, new CmsField("surname", "TEXT"));
        array_push($searchFields, new CmsField("skills", "TEXT"));

        return $searchFields;
    }

    public function cmsViewFields()
    {
        return [
            new CmsField('name', 'TEXT', 'amosperson', $this->attributeLabels()['name']),
            new CmsField('surname', 'TEXT', 'amosperson', $this->attributeLabels()['surname']),
            new CmsField('skills', 'TEXT', 'amosperson', $this->attributeLabels()['skills']),
        ];
    }

    /**
     * Method that search the latest research agid_person validated, typically limit is $ 3.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function lastAgidPerson($params, $limit = null)
    {
        return $this->searchAll($params, $limit);
    }

    /**
     * Search method useful to retrieve all non-deleted agid_person.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchAll($params, $limit = null)
    {
        return $this->search($params, "all", $limit);
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchAdminAll($params, $limit = null)
    {
        return $this->search($params, "admin-all", $limit);
    }
    
    /**
     * Method that searches all the news validated.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchOwnInterest($params, $limit = null)
    {
        return $this->search($params, "own-interest", $limit);
    }

    /**
     * Search method useful to retrieve validated AgidPerson with both primo_piano and in_evidenza flags = true.
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function searchHighlightedAndHomepageAgidPerson($params)
    {
        $query = $this->highlightedAndHomepageAgidPersonQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        // TBD FRANZ - vero o non vero ritorna sempre e comunque
        // lo stesso $dataProvider a che serve allora?
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * get the query used by the related searchHighlightedAndHomepageNews method
     * return just the query in case data provider/query itself needs editing
     *
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    public function highlightedAndHomepageAgidPersonQuery($params)
    {
        $now = date('Y-m-d');
        $tableName = $this->tableName();
        
        $query = $this->baseSearch($params)
            ->andWhere([
                $tableName . '.status' => AgidPerson::AGID_PERSON_STATUS_VALIDATED,
            ])
            ->andWhere([
                'deleted_at' => null
            ]);

        return $query;
    }

}
