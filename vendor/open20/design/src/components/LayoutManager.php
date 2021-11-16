<?php

namespace open20\design\components;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */
use open20\design\Module;
use Yii;
use yii\base\Component;
use open20\amos\core\controllers\AmosController;

class LayoutManager extends Component {

    public function renderMainLayout(array $params) {
        $basePath = Yii::$app->getModule(Module::getModuleName())->getBasePath();
        return Yii::$app->view->renderFile($basePath . '/views/layouts/bi-main-layout.php',
                        $params);
    }

    /**
     * 
     * @param AmosController $controller
     */
    public function setLayout(AmosController $controller) {
        $basePath = Yii::$app->getModule(Module::getModuleName())->getBasePath();
        $controller->layout =  '@vendor/open20/design/src/views/layouts/bi-main-layout';
    }

}
