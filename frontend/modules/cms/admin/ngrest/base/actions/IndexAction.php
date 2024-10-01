<?php

namespace app\modules\cms\admin\ngrest\base\actions;

use Yii;
use yii\data\ActiveDataProvider;
use luya\traits\CacheableTrait;
use app\modules\cms\components\AdminUser;

/**
 * List
 *
 * Returns all entries for the given model paginated by a number of elements.
 *
 * @since 1.0.0
 */
class IndexAction extends \luya\admin\ngrest\base\actions\IndexAction
{
    use CacheableTrait;

    /**
     * @var callable A callable which is executed.
     * @since 1.2.1
     */
    public $prepareActiveDataQuery;
    
    /**
     * Prepare the data models based on the ngrest find query.
     *
     * {@inheritDoc}
     *
     */
    protected function prepareDataProvider()
    {
        $isAdmin = AdminUser::isAdmin();
        
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        
        $filter = null;
        if ($this->dataFilter !== null) {
            $this->dataFilter = Yii::createObject($this->dataFilter);
            if ($this->dataFilter->load($requestParams)) {
                $filter = $this->dataFilter->build();
                if ($filter === false) {
                    return $this->dataFilter;
                }
            }
        }
        
        $query = call_user_func($this->prepareActiveDataQuery);
        if (!empty($filter)) {
            $query->andWhere($filter);
        }

        $modelName = array_pop(explode('\\', $this->modelClass));
  
        if($modelName == 'Group'){
            if(!$isAdmin){
                $query->andWhere(['<>','id',1]);
            }
        }elseif($modelName == 'User'){
            if(!$isAdmin){
                $query->leftJoin('admin_user_group g','g.user_id = admin_user.id');
                $query->andWhere(['<>','g.group_id',1]);
            }   
        }
       
        
        $dataProvider = Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
            'pagination' => $this->controller->pagination,
            'sort' => [
                'attributes' => $this->controller->generateSortAttributes($this->controller->model->getNgRestConfig()),
                'params' => $requestParams,
            ],
        ]);

        if ($this->isCachable() && $this->controller->cacheDependency) {
            Yii::$app->db->cache(function () use ($dataProvider) {
                $dataProvider->prepare();
            }, 0, Yii::createObject($this->controller->cacheDependency));
        }

        return $dataProvider;
    }
}
