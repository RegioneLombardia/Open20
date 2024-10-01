<?php

namespace open20\design\components;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */
use open20\design\Module;
use Yii;
use yii\base\Component;

class LayoutManager extends Component {

    public function renderMainLayout(array $params) {
        $basePath = Yii::$app->getModule(Module::getModuleName())->getBasePath();
        return Yii::$app->view->renderFile($basePath . '/views/layouts/bi-main-layout.php',
                        $params);
    }

    /**
     * 
     * @param \open20\amos\core\controllers\AmosController $controller
     */
    public function setLayout(\open20\amos\core\controllers\AmosController $controller) {
        $basePath = Yii::$app->getModule(Module::getModuleName())->getBasePath();
        $controller->layout =  '@vendor/open20/design/src/views/layouts/bi-main-layout';
    }

}
