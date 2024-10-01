<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-events/src/views 
 */
/**
* @var yii\web\View $this
* @var open20\amos\events\models\EventConfigurations $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Event Configurations',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Event Configurations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-configurations-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
