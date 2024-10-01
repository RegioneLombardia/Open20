<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\controllers
 * @category   CategoryName
 */

namespace open20\amos\documenti\controllers;

use open20\amos\dashboard\controllers\base\DashboardController;


class DefaultController extends DashboardController
{

    /**
     * @var string $layout Layout per la dashboard interna.
     */
    public $layout = 'dashboard_interna';

    /**
     * @inheritdoc
     */
    public function init()
    {

        parent::init();

        $this->setUpLayout();
    }

    /**
     * Lists all Documenti models.
     * @return mixed
     */
    public function actionIndex()
    {
        $url = '/documenti/documenti/own-interest-documents';
        $module = \Yii::$app->getModule('documenti');
        if ($module) {
            $url = $module->defaultWidgetIndexUrl;
        }

        return $this->redirect([$url]);
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            
            return true;
        }

        $this->layout = (!empty($layout)) ? $layout : $this->layout;

        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/' . $this->layout;
            }
        }

        return true;
    }

}
