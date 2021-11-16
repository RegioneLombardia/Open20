<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-events/src/views
 */
/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventCalendars $model
 */


$this->title = \open20\amos\events\AmosEvents::t('amosevents', 'Update calendar');
if($model->event_id){
    $this->title .= ' ' .Yii::t('amosevents', 'for event') .' "'. $event->title.'"';
}
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Event Calendars'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>

<div class="event-calendars-update">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
        'event' => $event,
        'dataProviderSlots' => $dataProviderSlots,
    ]) ?>

</div>
