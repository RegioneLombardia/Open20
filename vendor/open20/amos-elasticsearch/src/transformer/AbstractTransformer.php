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

use yii\base\BaseObject;


abstract class AbstractTransformer extends BaseObject
{
    const NETWORKS_INDEX = "networks";
    const TAGS_INDEX = "tags";
    const STATUS_INDEX = "status";
    
    const PLATFORM_TAG = "PLATFORM";
    const NOTAGS_TAG = "NOTAG";
    
    const DRAFT_VALUE = "draft";
    const TO_VALIDATE_VALUE = "tovalidate";
    const VALIDATED_VALUE = "validated";
    
    
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
     * @param string $objectClass
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;
    }
    
}
