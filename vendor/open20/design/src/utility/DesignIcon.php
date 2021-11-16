<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 18/02/2021
 * Time: 10:36
 */

namespace open20\design\utility;


use open20\design\assets\BootstrapItaliaDesignAsset;
use yii\helpers\Html;

class DesignIcon
{

    const ICON_MD = 'material-design';
    const ICON_BI = 'bootstrap-italia';

    /**
     * @param $iconName
     * @param string $framework
     * @param string $class
     * @param null $assetBundle
     * @return string
     */
    public static function show($iconName, $framework =  DesignIcon::ICON_MD,  $class = 'icon',  $assetBundle = null ){
        if(empty($assetBundle)){
            $assetBundle = BootstrapItaliaDesignAsset::register(\Yii::$app->controller->view);
        }
        $baseUrl = $assetBundle->baseUrl;
        if($framework == DesignIcon::ICON_MD){
            $path = 'sprite/material-sprite.svg';
        }else if(DesignIcon::ICON_BI){
            $path = 'node_modules/bootstrap-italia/dist/svg/sprite.svg';
        }
        return "<svg class='$class'><use xlink:href='$baseUrl/$path#$iconName'></use></svg>";
    }

}