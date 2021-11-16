<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

use open20\amos\events\AmosEvents;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\Event $model
 */

$this->title = AmosEvents::t('amosevents', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-update">
    <?= $this->render('_form', [
        'model' => $model,
        'upload' => $upload,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
        'moduleCwh' => $moduleCwh,
        'scope' => $scope
    ]) ?>
</div>
