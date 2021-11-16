<?php

namespace backend\modules\campains\models\search;

use backend\modules\campains\models\PreferenceCampain;
use backend\modules\operators\models\Operator;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PreferenceCampainSearch represents the model behind the search form about `backend\modules\campains\models\base\PreferenceCampain`.
 */
class PreferenceCampainSearch extends PreferenceCampain
{
public $struttura;
public $creatore;
    //private $container;

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['titolo', 'status','struttura', 'created_at', 'updated_at', 'deleted_at','creatore'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $limit = null)
    {
        $op = Operator::findOne(['user_id' => Yii::$app->user->id]);
        /** @var ActiveQuery $query */ 
	    if (empty($op->pc_structure_id)) {
            $query = PreferenceCampain::find();
        } else {
            $query = PreferenceCampain::find()->andWhere(['preference_campain.pc_structure_id' => $op->pc_structure_id]);
        }

        $query->orderBy('id DESC');
        $query->limit($limit);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'titlo' => [
                    'asc' => ['preference_campain.title' => SORT_ASC],
                    'desc' => ['preference_campain.title' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['preference_campain.vendorPath' => SORT_ASC],
                    'desc' => ['preference_campain.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['preference_campain.providerList' => SORT_ASC],
                    'desc' => ['preference_campain.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['preference_campain.actionButtonClass' => SORT_ASC],
                    'desc' => ['preference_campain.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['preference_campain.viewPath' => SORT_ASC],
                    'desc' => ['preference_campain.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['preference_campain.pathPrefix' => SORT_ASC],
                    'desc' => ['preference_campain.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['preference_campain.savedForm' => SORT_ASC],
                    'desc' => ['preference_campain.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['preference_campain.formLayout' => SORT_ASC],
                    'desc' => ['preference_campain.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['preference_campain.accessFilter' => SORT_ASC],
                    'desc' => ['preference_campain.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['preference_campain.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['preference_campain.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['preference_campain.singularEntities' => SORT_ASC],
                    'desc' => ['preference_campain.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['preference_campain.modelMessageCategory' => SORT_ASC],
                    'desc' => ['preference_campain.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['preference_campain.controllerClass' => SORT_ASC],
                    'desc' => ['preference_campain.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['preference_campain.modelClass' => SORT_ASC],
                    'desc' => ['preference_campain.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['preference_campain.searchModelClass' => SORT_ASC],
                    'desc' => ['preference_campain.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['preference_campain.baseControllerClass' => SORT_ASC],
                    'desc' => ['preference_campain.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['preference_campain.indexWidgetType' => SORT_ASC],
                    'desc' => ['preference_campain.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['preference_campain.enableI18N' => SORT_ASC],
                    'desc' => ['preference_campain.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['preference_campain.enablePjax' => SORT_ASC],
                    'desc' => ['preference_campain.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['preference_campain.messageCategory' => SORT_ASC],
                    'desc' => ['preference_campain.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['preference_campain.formTabs' => SORT_ASC],
                    'desc' => ['preference_campain.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['preference_campain.tabsFieldList' => SORT_ASC],
                    'desc' => ['preference_campain.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['preference_campain.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['preference_campain.relFiledsDynamic' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'preference_campain.created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);
        $query->andWhere( ['like', 'preference_campain.created_at', $this->created_at]);
        $query->joinWith('structure');
        $query->joinWith('createdUserProfile');
        $query->andFilterWhere(['like', 'preference_campain.titolo', $this->titolo])
            ->andFilterWhere(['like', 'preference_structure.id', $this->struttura])
            ->andFilterWhere(['like', 'CONCAT(user_profile.nome," ",user_profile.cognome)', $this->creatore])
            ->andFilterWhere(['like', 'preference_campain.status', $this->status]);

        return $dataProvider;
    }
}
