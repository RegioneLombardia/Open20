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
* @var open20\amos\events\models\EventContainer $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Event Container',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Event Container'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-container-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
        'arrayTemplates' => $arrayTemplates,
        'htmlTemplate' => $htmlTemplate
    ]) ?>

</div>
