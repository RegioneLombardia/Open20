<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

Yii::$app->controller->module->params['orderParams'] = null;
Yii::$app->controller->module->params['searchParams'] = null;
Yii::$app->view->params['createNewBtnParams'] = ['layout' => ''];

echo $this->render(
    '@vendor/open20/amos-documenti/src/widgets/graphics/views/documents-explorer/documents_explorer.php',
    [
        'explorer' => false,
    ]
);