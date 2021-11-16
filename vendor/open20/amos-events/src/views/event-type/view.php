<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-type
 * @category   CategoryName
 */

use open20\amos\core\forms\CloseButtonWidget;
use open20\amos\events\AmosEvents;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventType $model
 */

$this->title = strip_tags($model->title);
$this->params['breadcrumbs'][] = ['label' => AmosEvents::t('amosevents', 'Event Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-type-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'description:html',
            'color',
        ],
    ]) ?>
</div>

<?= CloseButtonWidget::widget([
    'title' => AmosEvents::t('amosevents', 'Close'),
    'layoutClass' => 'pull-right'
]) ?>
