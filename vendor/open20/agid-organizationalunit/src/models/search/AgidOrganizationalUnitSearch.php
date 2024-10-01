<?php

namespace open20\agid\organizationalunit\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use open20\amos\core\record\CmsField;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use open20\amos\core\interfaces\ContentModelSearchInterface;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitType;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitProfileType;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitContentTypeRoles;
use \open20\agid\person\models\AgidPerson;

class AgidOrganizationalUnitSearch extends AgidOrganizationalUnit implements SearchModelInterface, ContentModelSearchInterface,
    CmsModelInterface
{
    public $isSearch;

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agid_person_president_id', 'agid_person_vice_president_id', 'agid_organizational_unit_content_type_id', 'agid_organizational_unit_type_id',
                'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['short_description', 'skills', 'headquarters_name', 'address', 'public_hours', 'telephone_reference', 'mail_reference',
                'pec_reference', 'further_information', 'help_box', 'website_url'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'status'], 'string'],
            [['cap'], 'string'],
            [['telephone_reference'], 'string'],
            [['agid_organizational_unit_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidOrganizationalUnitType::className(),
                'targetAttribute' => ['agid_organizational_unit_type_id' => 'id']],
            // [['agid_organizational_unit_type_id', 'agid_organizational_unit_content_type_id', 'name', 'short_description', 'skills' ]],
            [['agid_person_president_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidPerson::className(), 'targetAttribute' => [
                    'agid_person_president_id' => 'id']],
            [['agid_person_vice_president_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidPerson::className(),
                'targetAttribute' => ['agid_person_vice_president_id' => 'id']],
            // ['logo_image', /*'maxFiles' => 1,*/ 'file', 'extensions' => 'jpeg, jpg, png, gif'],
            [['mail_reference', 'pec_reference'], 'email'],
            [['website_url'], 'url', 'defaultScheme' => 'https'],
            [['agid_organizational_unit_profile_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidOrganizationalUnitProfileType::className(),
                'targetAttribute' => ['agid_organizational_unit_profile_type_id' => 'id']],
            [['id_organizational_unit', 'telephone_internal_use', 'email_internal_use', 'fax'], 'string'],
            [['email_internal_use'], 'email'],
            [['priorita', 'agid_organizational_unit_profile_type_id'], 'integer'],
            [['priorita'], 'match', 'pattern' => '/^[0-9]+$/u'],
            [['notes_internal_use'], 'string']
        ];
    }

    /**
     * 
     * @param type $params
     * @return \open20\agid\organizationalunit\models\search\ActiveDataProvider
     */
    public function search($params, $queryType = NULL, $limit = NULL, $onlyDrafts = false, $pageSize = NULL)
    {

        $query = AgidOrganizationalUnit::find();
        //ricerca soloo per contenuti che posso vedere AGID

        if (\Yii::$app->getUser()->can('REDACTOR_ORGANIZATIONALUNIT')) {
            $ids = AgidOrganizationalUnitContentTypeRoles::find()->select('agid_organizational_unit_content_type_id')->andWhere([
                    'user_id' => \Yii::$app->getUser()->id])->distinct()->column();
            $query->andWhere([self::tableName().'.agid_organizational_unit_content_type_id' => $ids,]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('agidOrganizationalUnitType');

        $query->joinWith('agidOrganizationalUnitContentType');


        $query->distinct()->leftJoin(EntitysTagsMm::tableName(),
            EntitysTagsMm::tableName().".classname = '".str_replace('\\', '\\\\', AgidOrganizationalUnit::className())."' and ".EntitysTagsMm::tableName().".record_id = ".AgidOrganizationalUnit::tableName().".id and ".EntitysTagsMm::tableName().".deleted_at is NULL");

        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                ],
                'short_description' => [
                    'asc' => ['short_description' => SORT_ASC],
                    'desc' => ['short_description' => SORT_DESC],
                ],
        ]]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            AgidOrganizationalUnit::tableName().'.id' => $this->id,
            AgidOrganizationalUnit::tableName().'.agid_organizational_unit_content_type_id' => $this->agid_organizational_unit_content_type_id,
            AgidOrganizationalUnit::tableName().'.agid_organizational_unit_type_id' => $this->agid_organizational_unit_type_id,
            AgidOrganizationalUnit::tableName().'.created_at' => $this->created_at,
            AgidOrganizationalUnit::tableName().'.updated_at' => $this->updated_at,
            AgidOrganizationalUnit::tableName().'.deleted_at' => $this->deleted_at,
            AgidOrganizationalUnit::tableName().'.created_by' => $this->created_by,
            AgidOrganizationalUnit::tableName().'.updated_by' => $this->updated_by,
            AgidOrganizationalUnit::tableName().'.deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.name', $this->name])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.short_description', $this->short_description])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.skills', $this->skills])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.headquarters_name', $this->headquarters_name])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.address', $this->address])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.public_hours', $this->public_hours])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.cap', $this->cap])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.telephone_reference', $this->telephone_reference])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.mail_reference', $this->mail_reference])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.pec_reference', $this->pec_reference])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.further_information', $this->further_information])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.help_box', $this->help_box]);
        // ->andFilterWhere(['like', AgidOrganizationalUnit::tableName().'.status', $this->status]);

        return $dataProvider;
    }

    public function cmsIsVisible($id)
    {
        $retValue = true;
        return $retValue;
    }

    public function cmsSearch($params, $limit)
    {
        $params       = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $dataProvider = $this->search($params);
        $query        = $dataProvider->query;
        $i            = 0;
        foreach ($this->agid_organizational_unit_content_type_id as $id) {
            if ($i == 0) {
                $query->andFilterWhere(['like', 'agid_organizational_unit_content_type_id', $id]);
            } else {
                $query->orFilterWhere(['like', 'agid_organizational_unit_content_type_id', $id]);
            }
            $i++;
        }
        if ($params["withPagination"]) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }
        $query->andWhere([AgidOrganizationalUnit::tableName().'.status' => AgidOrganizationalUnit::AGID_ORGANIZATIONAL_UNIT_WORKFLOW_STATUS_VALIDATED,]);
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
        array_push($searchFields, new CmsField("short_description", "TEXT"));
        array_push($searchFields, new CmsField("skills", "TEXT"));

        return $searchFields;
    }

    public function cmsViewFields()
    {
        return [
            new CmsField('name', 'TEXT', 'amosorganizationalunit', $this->attributeLabels()['name']),
            new CmsField('short_description', 'TEXT', 'amosorganizationalunit',
                $this->attributeLabels()['short_description']),
            new CmsField('skills', 'TEXT', 'amosorganizationalunit', $this->attributeLabels()['skills']),
        ];
    }

    public function convertToSearchResult($model)
    {
        return null;
    }

    public function globalSearch($searchParamsArray, $pageSize = 5)
    {
        return null;
    }

    public function searchAllQuery($params)
    {
        return null;
    }

    public function searchCreatedByMeQuery($params)
    {
        return null;
    }

    public function searchDefaultOrder($dataProvider)
    {
        return null;
    }

    public function searchOwnInterestsQuery($params)
    {
        return null;
    }

    public function searchToValidateQuery($params)
    {
        return null;
    }

    /**
     * Method that search the latest research agid_organizational_unit validated, typically limit is $ 3.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function lastAgidOrganizationalUnit($params, $limit = null)
    {
        return $this->searchAll($params, $limit);
    }

    /**
     * Search method useful to retrieve all non-deleted agid_organizational_unit.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchAll($params, $limit = null)
    {
        return $this->search($params, "all", $limit);
    }

    public function searchValidated($params, $limit = null)
    {
        $search = $this->search($params, 'all', $limit);
        $search->query->andWhere(['agid_organizational_unit.status' => AgidOrganizationalUnit::AGID_ORGANIZATIONAL_UNIT_WORKFLOW_STATUS_VALIDATED])
            ->orderBy(new \yii\db\Expression("CONVERT(COALESCE(agid_organizational_unit.priorita, '9999999999'),UNSIGNED)  ASC, agid_organizational_unit.name asc"));

        return $search;
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
     * Search method useful to retrieve validated AgidOrganizationaUnit with both primo_piano and in_evidenza flags = true.
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function searchHighlightedAndHomepageAgidOrganizationalUnit($params)
    {
        $query = $this->highlightedAndHomepageAgidOrganizationalUnitQuery($params);

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
    public function highlightedAndHomepageAgidOrganizationalUnitQuery($params)
    {
        $now       = date('Y-m-d');
        $tableName = $this->tableName();

        $query = $this->baseSearch($params)
            ->andWhere([
                $tableName.'.status' => AgidOrganizationalUnit::AGID_ORGANIZATIONAL_UNIT_WORKFLOW_STATUS_VALIDATED,
            ])
            ->andWhere([
            'deleted_at' => null
        ]);

        return $query;
    }
}