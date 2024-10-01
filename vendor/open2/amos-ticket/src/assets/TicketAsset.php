<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\assets
 * @category   CategoryName
 */

namespace open2\amos\ticket\assets;

use open20\amos\core\widget\WidgetAbstract;
use yii\web\AssetBundle;

/**
 * Class TicketAsset
 * @package open2\amos\ticket\assets
 */
class TicketAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open2/amos-ticket/src/assets/web';

    /**
     * @inheritdoc
     */
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'less/ticket.less',
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/ticket.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset'
    ];

    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->css = ['less/ticket_fullsize.less','less/ticket-bi.less'];
        }

        parent::init();
    }
}
