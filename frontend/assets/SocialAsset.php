<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */
/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\assets;

use luya\web\Asset;
use Yii;

/**
 * Description of SocialAsset
 *
 */
class SocialAsset extends Asset
{
    public $sourcePath     = '@app/resources/social';
    public $css            = [
        'css/style.css'
    ];
    public $js             = [
    ];
    public $publishOptions = [
        'only' => [
            'css/*',
            'js/*',
        ]
    ];
    public $depends        = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        $moduleL = Yii::$app->getModule('layout');
        if (!empty($moduleL)) {
            $this->depends [] = 'open20\amos\layout\assets\IconAsset';
        } else {
            $this->depends [] = 'open20\amos\core\views\assets\AmosIconAsset';
        }
        parent::init();
    }
}