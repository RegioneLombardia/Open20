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

use open20\elasticsearch\Module;
use Yii;
use yii\base\BaseObject;

class TransformerManagers extends BaseObject
{
    private $module = null;
    private $trasformers = [];
    
    
    public function __construct($module,$config = array()) 
    {
        parent::__construct($config);
        $this->module = $module;
        $this->loadTransformers();
    }
    
    public function init() 
    {
        parent::init();
    }

    /**
     * 
     */
    public function loadTransformers()
    {
        foreach($this->module->modelsEnabled as $key=>$value)
        {
            $this->trasformers[$key] =  Yii::createObject($value);
        }
    }

    /**
     * 
     * @param type $key
     * @return type
     */
    public function getTrasnformerManager($key)
    {
        $transformer = null;
        
        if(array_key_exists($key, $this->trasformers))
        {
            $transformer = $this->trasformers[$key];
        }
        
        return $transformer;
        
    }

}
