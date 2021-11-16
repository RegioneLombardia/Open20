<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report
 * @category   CategoryName
 */

namespace open20\amos\report\widgets;

use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\record\Record;
use open20\amos\report\AmosReport;
use open20\amos\report\models\Report;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class TabReportsWidget
 * @package open20\amos\report\widgets
 */
class TabReportsWidget extends Widget
{
    /**
     * @var Record $model
     */
    public $model = null;

    /**
     * @return Record
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Record $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * widget initialization
     */
    public function init()
    {
        parent::init();

        if (is_null($this->getModel())) {
            throw new \Exception(BaseAmosModule::t('amosreport', 'Missing Model'));
        }
    }

    public function run()
    {
        $model = $this->getModel();
        /** @var ActiveQuery $query */
        $query = Report::find()->andWhere([
            'classname' => $model->className(),
            'context_id' => $model->id
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'pagination' => [
//                'pageSize' => $limit,
//            ]
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC,
                    'created_at' => SORT_ASC
                ]
            ]
        ]);

        return $this->render('tab-reports', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }


}