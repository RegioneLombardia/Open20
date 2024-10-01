<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 04/09/2018
 * Time: 16:42
 */

namespace amos\sitemanagement\models;


use yii\base\Model;

class SiteManagmentLandingRoutes extends Model
{
    public $whiteListModuleRoutes;
    public $blackListModuleRoutes;


    public function init()
    {
        $module = \Yii::$app->getModule('sitemanagement');
        if($module){
            $this->whiteListModuleRoutes = $module->whiteListModuleRoutes;
            $this->blackListModuleRoutes = $module->blackListModuleRoutes;
        }
        parent::init(); //
    }

    /**
     * @return array
     */
    public function getRoutes(){
        $route = new \mdm\admin\models\Route();
        $modules = \Yii::$app->getModules();
        $rotte = [];
        foreach ($modules as $key => $value) {
            if((!empty($this->whiteListModuleRoutes ) && in_array($key, $this->whiteListModuleRoutes)) || empty($this->whiteListModuleRoutes ) && !in_array($key, $this->blackListModuleRoutes)) {
                $rotte = array_merge($rotte, $route->getAppRoutes($key));
            }
        }
        $Rotte = [];
        foreach ($rotte as $rotta) {
            if (!strpos($rotta, '*')) {
                $Rotte[$rotta] = $rotta;
            } else if (strpos($rotta, '*')) {
                $rotta = str_replace('/*', '', $rotta);
                $Rotte[$rotta] = $rotta;
            }
        }


        return $Rotte;
    }

}