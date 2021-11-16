<?php

namespace backend\modules\userimporter\models\search;

use backend\modules\userimporter\models\UserImportTask;
use open20\amos\events\utility\EventsUtility;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\VarDumper;

/**
 * UserImporterTaskSearch represents the model behind the search form about `UserImportTask`.
 */
class UserImportTaskSearch extends UserImportTask
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
            [['id', 'user_id', 'tot_input_processed',
                'tot_input_imported', 'file_input', 'file_success_input', 'file_errors_input'],
                'integer'],
            [['name', 'task_date'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = UserImportTask::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['task_date'=>SORT_DESC],
            'attributes' => [
                'name' => [
                    'asc' => ['user_import_task.name' => SORT_ASC],
                    'desc' => ['user_import_task.name' => SORT_DESC],
                ],
                'task_date' => [
                    'asc' => ['user_import_task.task_date' => SORT_ASC],
                    'desc' => ['user_import_task.task_date' => SORT_DESC],
                ],
                'status' => [
                    'asc' => ['user_import_task.status' => SORT_ASC],
                    'desc' => ['user_import_task.status' => SORT_DESC],
                ],
                ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }



        $query->andFilterWhere([
            'id' => $this->id,
//            'task_date' => $this->task_date,
            'user_id' => $this->user_id,
            'tot_input_processed' => $this->tot_input_processed,
            'tot_input_imported' => $this->tot_input_imported,
            'file_input' => $this->file_input,
            'file_success_input' => $this->file_success_input,
            'file_errors_input' => $this->file_errors_input,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        if (!empty($this->task_date)) {
            $query->andFilterWhere(['=', 'DATE(task_date)', new Expression('DATE(\''.$this->task_date.'\')')]);
        }

//        VarDumper::dump($query->createCommand()->rawSql,3,true);
        return $dataProvider;
    }
}