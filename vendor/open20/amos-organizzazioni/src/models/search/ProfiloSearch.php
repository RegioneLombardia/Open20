<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\models\search
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\models\search;

use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\record\CmsField;

/**
 * Class ProfiloSearch
 * ProfiloSearch represents the model behind the search form about `open20\amos\organizzazioni\models\Profilo`.
 * @package open20\amos\organizzazioni\models\search
 */
class ProfiloSearch extends Profilo implements SearchModelInterface, CmsModelInterface
{
    public $isSearch = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'name',
                'partita_iva',
                'istat_code',
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function baseSearch($params)
    {
        //init the default search values
        $this->initOrderVars();

        //check params to get orders value
        $this->setOrderVars($params);

        /** @var Profilo $className */
        $className = $this->organizzazioniModule->model('Profilo');

        return $className::find()->distinct();
    }

    /**
     * @inheritdoc
     */
    public function searchFieldsLike()
    {
        return [
            'name',
            'partita_iva',
            'istat_code',
        ];
    }

    /**
     * @inheritdoc
     */
    public function searchFieldsGlobalSearch()
    {
        return [
            'name',
            'partita_iva',
            'istat_code',
        ];
    }

    /**
     * @inheritdoc
     */
    public function filterValidated($query)
    {
        $module = \Yii::$app->getModule('organizzazioni');
        if ($module->enableWorkflow) {
            $query->andWhere([static::tableName() . '.status' => Profilo::PROFILO_WORKFLOW_STATUS_VALIDATED]);
        }
    }

    /**
     * @param array $params
     * @param null $limit
     * @return \yii\data\ActiveDataProvider|\yii\data\BaseDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchToValidateProfilo($params, $limit = null)
    {
        return $this->search($params, 'to-validate', $limit);
    }

    /**
     *  Search for organizations whose the logged user belongs
     * @param $params
     * @param null $limit
     * @param bool $onlyActiveStatus
     * @return \yii\data\ActiveDataProvider|\yii\data\BaseDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchMyOrganizations($params, $limit = null, $onlyActiveStatus = false)
    {
        $dataProvider = $this->search($params, 'own-interest');
        return $dataProvider;
    }

    /**
     * @param $params
     * @param null $limit
     * @return \open20\amos\core\interfaces\ActiveDataProvider|\yii\data\ActiveDataProvider|\yii\data\BaseDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function cmsSearch($params, $limit = null)
    {
        $dataProvider = $this->search($params, 'all', $limit);
        return $dataProvider;
    }

    /**
     * @param $params
     * @param null $limit
     * @return \open20\amos\core\interfaces\ActiveDataProvider|\yii\data\ActiveDataProvider|\yii\data\BaseDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function cmsMyOrganizationsSearch($params, $limit = null)
    {
        $dataProvider = $this->search($params, 'own-interest', $limit);
        return $dataProvider;
    }


    /**
     * @inheritdoc
     */
    public function cmsViewFields()
    {
        $viewFields = [];
        array_push($viewFields, new CmsField("name", "TEXT", 'amosorganizzazioni', $this->attributeLabels()["name"]));
        array_push($viewFields,
            new CmsField("presentazione_della_organizzaz", "TEXT", 'amosorganizzazioni',
                $this->attributeLabels()['presentazione_della_organizzaz']));
        array_push($viewFields,
            new CmsField("modelImage", "IMAGE", 'amosorganizzazioni', $this->attributeLabels()['modelImage']));
        return $viewFields;
    }

    /**
     * @inheritdoc
     */
    public function cmsSearchFields()
    {
        $searchFields = [];

        array_push($searchFields, new CmsField("name", "TEXT"));
        array_push($searchFields, new CmsField("presentazione_della_organizzaz", "TEXT"));

        return $searchFields;
    }

    /**
     * @inheritdoc
     */
    public function cmsIsVisible($id)
    {
        $retValue = true;
        return $retValue;
    }
}