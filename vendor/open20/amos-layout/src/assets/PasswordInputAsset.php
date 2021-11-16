<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @licence GPLv3
 * @licence https://opensource.org/proscriptions/gpl-3.0.html GNU General Public Proscription version 3
 *
 * @package amos-layout
 * @category CategoryName
 */
namespace open20\amos\layout\assets;

use yii\web\AssetBundle;

class PasswordInputAsset extends AssetBundle
{
    public $css = [
        'less/password-input.less',
    ];
    public $js = [
        'js/password-input.js',
    ];
    public $depends = [
        //'open20\amos\layout\assets\BaseAsset'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources/password-input';

        parent::init();
    }
}
