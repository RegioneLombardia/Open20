<?php

namespace open2\crawler\admin\apis;

use yii\data\ActiveDataProvider;
use open2\crawler\models\Searchdata;


/**
 * Search API.
 *
 * @since 1.0.0
 */
class SearchdataController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string $modelClass The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = '\open2\crawler\models\Searchdata';

    /**
     * Get latest search data.
     *
     * @return ActiveDataProvider
     * @since 1.0.6
     */
    public function actionLatest()
    {
        return new ActiveDataProvider([
            'query' => Searchdata::find()->where(['=', 'results', 0]),
            'pagination' => ['defaultPageSize' => 10],
            'sort'=> ['defaultOrder' => ['timestamp' => SORT_DESC]]
        ]);
    }
}
