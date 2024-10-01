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
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class ModuleDocumentiHierarchyBefeAsset
 * @package open20\amos\documenti\assets
 */
class ModuleDocumentiHierarchyBefeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-documenti/src/assets/web';

    /**
     * @inheritdoc
     */
    public $css = [
        'less/hierarchical_documents_bi.less'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
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
        $moduleL = \Yii::$app->getModule('layout');

        /* if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
            $this->css = ['less/documents_fullsize.less', 'less/document_design_bi.less'];
        } */

       /*  if (!empty($moduleL)) {
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        } */
        parent::init();
    }
}
