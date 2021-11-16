<?php

namespace open20\amos\events\models\search;

use open20\amos\events\AmosEvents;
use open20\amos\events\models\EventAccreditationList;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EventAccreditationListSearch represents the model behind the search form about `open20\amos\events\models\EventAccreditationList`.
 */
class EventAccreditationListSearch extends EventAccreditationList
{

    //private $container;
    public $isSearch = true;

    /**
     * @inheritdoc
     */
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
            [['id', 'event_id', 'position', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['Event', 'safe'],
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

    public function search($params)
    {
        /** @var AmosEvents $eventsModule */
        $eventsModule = AmosEvents::instance();
        /** @var EventAccreditationList $eventAccreditationListModel */
        $eventAccreditationListModel = $eventsModule->createModel('EventAccreditationList');

        $query = $eventAccreditationListModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('event');

        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['event_accreditation_list.template' => SORT_ASC],
                    'desc' => ['event_accreditation_list.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['event_accreditation_list.vendorPath' => SORT_ASC],
                    'desc' => ['event_accreditation_list.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['event_accreditation_list.providerList' => SORT_ASC],
                    'desc' => ['event_accreditation_list.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['event_accreditation_list.actionButtonClass' => SORT_ASC],
                    'desc' => ['event_accreditation_list.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['event_accreditation_list.viewPath' => SORT_ASC],
                    'desc' => ['event_accreditation_list.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['event_accreditation_list.pathPrefix' => SORT_ASC],
                    'desc' => ['event_accreditation_list.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['event_accreditation_list.savedForm' => SORT_ASC],
                    'desc' => ['event_accreditation_list.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['event_accreditation_list.formLayout' => SORT_ASC],
                    'desc' => ['event_accreditation_list.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['event_accreditation_list.accessFilter' => SORT_ASC],
                    'desc' => ['event_accreditation_list.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['event_accreditation_list.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['event_accreditation_list.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['event_accreditation_list.singularEntities' => SORT_ASC],
                    'desc' => ['event_accreditation_list.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['event_accreditation_list.modelMessageCategory' => SORT_ASC],
                    'desc' => ['event_accreditation_list.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['event_accreditation_list.controllerClass' => SORT_ASC],
                    'desc' => ['event_accreditation_list.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['event_accreditation_list.modelClass' => SORT_ASC],
                    'desc' => ['event_accreditation_list.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['event_accreditation_list.searchModelClass' => SORT_ASC],
                    'desc' => ['event_accreditation_list.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['event_accreditation_list.baseControllerClass' => SORT_ASC],
                    'desc' => ['event_accreditation_list.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['event_accreditation_list.indexWidgetType' => SORT_ASC],
                    'desc' => ['event_accreditation_list.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['event_accreditation_list.enableI18N' => SORT_ASC],
                    'desc' => ['event_accreditation_list.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['event_accreditation_list.enablePjax' => SORT_ASC],
                    'desc' => ['event_accreditation_list.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['event_accreditation_list.messageCategory' => SORT_ASC],
                    'desc' => ['event_accreditation_list.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['event_accreditation_list.formTabs' => SORT_ASC],
                    'desc' => ['event_accreditation_list.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['event_accreditation_list.tabsFieldList' => SORT_ASC],
                    'desc' => ['event_accreditation_list.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['event_accreditation_list.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['event_accreditation_list.relFiledsDynamic' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'event_id' => $this->event_id,
            'position' => $this->position,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
