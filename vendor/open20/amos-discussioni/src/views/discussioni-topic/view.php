<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\core\utilities\ViewUtility;
use open20\amos\discussioni\AmosDiscussioni;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\DiscussioniTopic $model
 */

/** @var \open20\amos\discussioni\controllers\DiscussioniTopicController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();

$this->title = $model->titolo;

$this->params['breadcrumbs'][] = ['label' => AmosDiscussioni::t('amosdiscussioni', 'Discussioni'), 'url' => ['/discussioni']];
$this->params['breadcrumbs'][] = ['label' => AmosDiscussioni::t('amosdiscussioni', 'Topic'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discussioni-topic-view">
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        //'id',
        'titolo',
        'testo:html',
        ['attribute' => 'created_at', 'format' => ['datetime', ViewUtility::formatDateTime()]],
        ['attribute' => 'updated_at', 'format' => ['datetime', ViewUtility::formatDateTime()]],
        ['attribute' => 'deleted_at', 'format' => ['datetime', ViewUtility::formatDateTime()]],
        'created_by',
        'updated_by',
        'deleted_by',
        'version',
    ],
])
?>
</div>
