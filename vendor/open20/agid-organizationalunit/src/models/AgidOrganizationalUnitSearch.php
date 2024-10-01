<?php

namespace open20\agid\organizationalunit\models;

use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AgidOrganizationalUnitSearch represents the model behind the search form about `app\models\AgidOrganizationalUnit`.
 */
class AgidOrganizationalUnitSearch extends AgidOrganizationalUnit
{

	//private $container;

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'agid_organizational_unit_content_type_id', 'agid_organizational_unit_type_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'short_description', 'skills', 'headquarters_name', 'address', 'public_hours', 'cap', 'telephone_reference', 'mail_reference', 'pec_reference', 'further_information', 'help_box', 'status', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['AgidOrganizationalUnitType', 'safe'],
        ];
    }

    public function scenarios()
    {
		// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AgidOrganizationalUnit::find();
        if(\Yii::$app->getUser()->can('REDACTOR_ORGANIZATIONALUNIT')){
            $ids = AgidOrganizationalUnitContentTypeRoles::find()->select('agid_organizational_unit_content_type_id')->andWhere(['user_id' =>\Yii::$app->getUser()->id ])->distinct()->column();
            $query->andWhere([self::tableName() .'.agid_organizational_unit_content_type_id' => $ids,]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('agidOrganizationalUnitType');

        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['agid_organizational_unit.template' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['agid_organizational_unit.vendorPath' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['agid_organizational_unit.providerList' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['agid_organizational_unit.actionButtonClass' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['agid_organizational_unit.viewPath' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['agid_organizational_unit.pathPrefix' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['agid_organizational_unit.savedForm' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['agid_organizational_unit.formLayout' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['agid_organizational_unit.accessFilter' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['agid_organizational_unit.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['agid_organizational_unit.singularEntities' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['agid_organizational_unit.modelMessageCategory' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['agid_organizational_unit.controllerClass' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['agid_organizational_unit.modelClass' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['agid_organizational_unit.searchModelClass' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['agid_organizational_unit.baseControllerClass' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['agid_organizational_unit.indexWidgetType' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['agid_organizational_unit.enableI18N' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['agid_organizational_unit.enablePjax' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['agid_organizational_unit.messageCategory' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['agid_organizational_unit.formTabs' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['agid_organizational_unit.tabsFieldList' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['agid_organizational_unit.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['agid_organizational_unit.relFiledsDynamic' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'agid_organizational_unit_content_type_id' => $this->agid_organizational_unit_content_type_id,
            'agid_organizational_unit_type_id' => $this->agid_organizational_unit_type_id,
            AgidOrganizationalUnit::tableName() . '.created_at' => $this->created_at,
            AgidOrganizationalUnit::tableName() . '.updated_at' => $this->updated_at,
            AgidOrganizationalUnit::tableName() . '.deleted_at' => $this->deleted_at,
            AgidOrganizationalUnit::tableName() . '.created_by' => $this->created_by,
            AgidOrganizationalUnit::tableName() . '.updated_by' => $this->updated_by,
            AgidOrganizationalUnit::tableName() . '.deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.name', $this->name])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.short_description', $this->short_description])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.skills', $this->skills])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.headquarters_name', $this->headquarters_name])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.address', $this->address])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.public_hours', $this->public_hours])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.cap', $this->cap])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.telephone_reference', $this->telephone_reference])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.mail_reference', $this->mail_reference])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.pec_reference', $this->pec_reference])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.further_information', $this->further_information])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.help_box', $this->help_box])
            ->andFilterWhere(['like', AgidOrganizationalUnit::tableName() . '.status', $this->status]);

        // UPDATE FROM / TO 
        $class_name = end(explode("\\", $this::className()));

        if( !empty($params[$class_name]['updated_from']) ){

            $query->andWhere(['>=', AgidOrganizationalUnit::tableName() . '.updated_at', $params[$class_name]['updated_from'] ]);
        }

        if( !empty($params[$class_name]['updated_to']) ){

            $query->andWhere(['<=', AgidOrganizationalUnit::tableName() . '.updated_at', $params[$class_name]['updated_to'] ]);
        }

        return $dataProvider;
    }
}
