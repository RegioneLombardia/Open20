<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\assets
 * @category   CategoryName
 */

namespace open20\amos\documenti\assets;

use yii\web\AssetBundle;

/**
 * Class ModuleDocumentiDocumentsExplorerAsset
 * @package open20\amos\documenti\assets
 */
class ModuleDocumentiDocumentsExplorerAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-documenti/src/assets/web';

    /**
     * @inheritdoc
     */
    public $css = [
        //'less/documents.less',
        //'less/hierarchical-documents.less',
        //'css/materialize/materialize.min.css',
        //'css/materialize/sidenav.css',
        'less/documents-explorer.less',
        'css/jquery.modal.min.css',
        'css/jquery.contextMenu.min.css',
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/documents-explorer/lajax.js',
        'js/documents.js',
        'js/documents-explorer/mustache.min.js',
        'js/documents-explorer/jquery.modal.min.js',
        'js/documents-explorer/jquery.ui.position.min.js',
        'js/documents-explorer/jquery.contextMenu.min.js',
        'js/documents-explorer/underscore-min.js',
        'js/documents-explorer/documents-explorer.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        // TODO Disable force copy
        \Yii::$app->assetManager->forceCopy = true;
        $moduleL = \Yii::$app->getModule('layout');
        if (!empty($moduleL)) {
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}
