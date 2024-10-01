<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */

/** @var \open20\amos\documenti\controllers\DocumentiController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
$isFolder = $controller->documentIsFolder($model);
if ($isFolder) {
    $this->title = AmosDocumenti::t('amosdocumenti', '#create_folder_title');
} else {
    $this->title = AmosDocumenti::t('amosdocumenti', 'Inserisci documento');
}
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', Yii::$app->session->get('previousTitle')), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documenti-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>