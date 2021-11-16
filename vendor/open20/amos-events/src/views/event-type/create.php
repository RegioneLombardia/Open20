<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-type
 * @category   CategoryName
 */

use open20\amos\events\AmosEvents;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventType $model
 */

$this->title = AmosEvents::t('amosevents', 'Create');
$this->params['breadcrumbs'][] = ['label' => AmosEvents::t('amosevents', 'Event Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-type-create">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>
</div>
