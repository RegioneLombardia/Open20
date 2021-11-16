<?php

namespace app\modules\cms\base;


use yii\helpers\Url;
use yii\helpers\Html;
use Lullabot\AMP\AMP;
use Lullabot\AMP\Validate\Scope;

class DefaultController extends \luya\cms\frontend\controllers\DefaultController
{
    
    private $queryVars = [
        'amp',
        'path'
    ];
    
    
    /**
     * 
     * @return boolean
     */
    private function isAmp()
    {
        $is = false;
        
        foreach ($this->queryVars as $var)
        {
            $value = \Yii::$app->request->getQueryParam($var);
            if(!empty($value))
            {
                if($value == 'amp')
                {
                    $is = true;
                }
                break;
            }
        }
        return $is;
    }
    
    /**
     * 
     * @param type $view
     * @param type $params
     * @return type
     */
    public function render($view, $params = array()) {
        
        $html = null;
        
        if ($this->isAmp()) 
        {
            $this->layout = "@app/views/layouts/main_amp";
        }

        $html = parent::render($view, $params);
        return $html;
    }
    
     public function renderContent($view) {
        
        $html = null;
        
        if ($this->isAmp()) 
        {
            $this->layout = "@app/views/layouts/main_amp";
        }
        
        $html = parent::renderContent($view);
        return $html;
    }
    
}
