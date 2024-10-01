<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */
namespace open20\elasticsearch\transformer;

use open20\elasticsearch\base\interfaces\TransformerManagerInterface;
use yii\base\BaseObject;


abstract class AbstractTransformerManager extends BaseObject implements TransformerManagerInterface
{
    
    /**
     * Class of the model to map to the elastica documents.
     *
     * @var string
     */
    protected $objectClass = null; 
    
    /**
     * Returns the object class that is used for conversion.
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }
    
    /**
     * 
     * @param ActiveRecord $model
     */
    public function modelToElastic($model)
    {
        $transformer = $this->getModelToElasticTransformer();
        return $transformer->cwh($transformer->setModel($model)->transform());
    }
    
    /**
     * 
     * @param ActiveRecord $model
     * @return boolean
     */
    public function canSaveIndex($model)
    {
        return $this->getModelToElasticTransformer()->setModel($model)->canSaveIndex();
    }
    
    /**
     * 
     * @param array $index
     */
    public function elasticToModel($index)
    {
        return $this->getElasticToModelTransformer()->setElasticObject($index)->dropValues()->transform();
    }
}
