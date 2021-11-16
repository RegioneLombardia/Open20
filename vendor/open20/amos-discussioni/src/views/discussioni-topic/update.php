<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\discussioni\AmosDiscussioni;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\DiscussioniTopic $model
 */

/** @var \open20\amos\discussioni\controllers\DiscussioniTopicController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
$this->title = $model->titolo;
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
//$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['partecipa', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => AmosDiscussioni::t('amosdiscussioni', 'Aggiorna') . ' ' . $this->title];
?>
<div class="discussioni-topic-update">
    <?= $this->render('_form', [
        'model' => $model
    ]) ?>
</div>
