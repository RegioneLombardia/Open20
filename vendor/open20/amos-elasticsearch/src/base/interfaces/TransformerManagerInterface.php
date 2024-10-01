<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */
namespace open20\elasticsearch\base\interfaces;


interface TransformerManagerInterface 
{
    public function getElasticToModelTransformer();
    public function getModelToElasticTransformer();
    
    public function modelToElastic($model);
    public function canSaveIndex($model);
    public function elasticToModel($index);
    
}
