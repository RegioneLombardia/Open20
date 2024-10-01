<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @licence GPLv3
 * @licence https://opensource.org/licenses/gpl-3.0.html GNU General Public Proscription version 3
 *
 * @package amos-layout
 * @category CategoryName
 */
namespace open20\amos\layout\assets;

use yii\web\AssetBundle;

class ExitFormWithoutSaveAsset extends AssetBundle
{
    
    public $css = [
        
    ];
    public $js = [
        'js/exit-form-without-save.js',
    ];
    public $depends = [
        //'open20\amos\layout\assets\BaseAsset'
    ];
    /**
     * tell the form, if you like to render form check events within the view
     * @var boolean
     */
    public $formSave = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources/form';

        parent::init();
    }
}
