<?php


namespace open20\amos\layout\utility;


use open20\amos\core\helpers\Html;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;

class AmosIconsBi
{
    /**
     * @param $name
     * @param string $type
     * @return string
     */
    public static function show($name, $type = 'secondary'){
        if($type == 'danger'){
            $class = 'rounded-danger';
        }
        else if ($type == 'primary') {
            $class = 'rounded-primary';
        }
        else {
            $class = 'rounded-secondary';
        }
        $spriteAsset = BootstrapItaliaCustomSpriteAsset::register(\Yii::$app->controller->getView());
        $icon = "<use xlink:href=" .  $spriteAsset->baseUrl . "/material-sprite.svg#$name></use>";
        $svgIcon = Html::tag('svg', $icon, ['class' => 'icon icon-white']);
        $spanSvgIcon = Html::tag('span', $svgIcon, ['class' => "rounded-icon $class p-1"]);

        return $spanSvgIcon;
    }

}