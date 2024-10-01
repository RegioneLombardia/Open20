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
$this->title = \yii\helpers\Html::decode($model->titolo);
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = AmosDocumenti::t('amosdocumenti', 'Aggiorna');
?>

<div class="documenti-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
