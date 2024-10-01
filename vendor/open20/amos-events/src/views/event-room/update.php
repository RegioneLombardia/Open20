<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-room
 * @category   CategoryName
 */

use open20\amos\events\AmosEvents;

/**
 * @var yii\web\View $this
 * @var \open20\amos\events\models\EventRoom $model
 */

$this->title = AmosEvents::t('amosevents', 'Update room');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="event-room-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
