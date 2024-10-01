<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 30/09/2020
 * Time: 11:30
 */

namespace open20\amos\utility\models\search;


use open20\amos\utility\models\AuthItem;
use yii\data\ActiveDataProvider;
use yii\rbac\Permission;

class AuthItemSearch extends AuthItem
{

    public $isSearch = true;


//private $container;

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [[ 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description'], 'safe'],
        ];
    }


    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params){
        $query = AuthItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'name' => $this->name,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'deleted_by' => $this->deleted_by,
            'deleted_at' => $this->deleted_at,
        ]);

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchRoles($params){
        $query = AuthItem::find()
        ->andWhere(['type' => Permission::TYPE_ROLE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->pagination->pageSize = 40;
        if (!($this->load($params) )) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'like', 'name', $this->name
        ]);

        return $dataProvider;
    }


}