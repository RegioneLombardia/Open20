<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */
namespace open20\elasticsearch\base;

use yii\data\BaseDataProvider;

class ElasticDataProvider extends BaseDataProvider
{
    
    public $query = null;
    
    /**
     * @var string|callable name of the key column or a callable returning it
     */
    public $key = 'id';
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }
 
    /**
     * {@inheritdoc}
     */
    protected function prepareModels()
    {
        $models = [];
        $pagination = $this->getPagination();
 
        if ($pagination === false) {
            $models = $this->query->all();
        } else {
            // in case there's pagination, read only a single page
            $pagination->totalCount = $this->getTotalCount();
            $this->query->from($pagination->getOffset());
            $this->query->limit($pagination->getLimit());
 
            $models = $this->query->all();
        }
 
        return $models;
    }
 
    /**
     * {@inheritdoc}
     */
    protected function prepareKeys($models)
    {
        if ($this->key !== null) {
            $keys = [];
 
            foreach ($models as $model) {
                if (is_string($this->key)) {
                    $keys[] = $model[$this->key];
                } else {
                    $keys[] = call_user_func($this->key, $model);
                }
            }
 
            return $keys;
        }

        return array_keys($models);
    }
 
    /**
     * {@inheritdoc}
     */
    protected function prepareTotalCount()
    {
        $count = 0;
        $count = $this->query->count();
        return $count;
    }
}
