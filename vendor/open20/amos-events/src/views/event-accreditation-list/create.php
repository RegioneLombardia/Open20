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
* @var open20\amos\events\models\EventAccreditationList $model
* @var int $eventId
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Event Accreditation List',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Event Accreditation List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-accreditation-list-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    'eventId' => $eventId
    ]) ?>

</div>
