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
* @var open20\amos\events\models\EventLocation $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Event Location',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Event Location'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-location-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
        'dataProviderEntrance' => $dataProviderEntrance

    ]) ?>

</div>
