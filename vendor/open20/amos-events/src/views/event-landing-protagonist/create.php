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
* @var open20\amos\events\models\EventLandingProtagonist $model
*/

$this->title = Yii::t('amoscore', "Aggiunti un protagonista per l'evento '{title}'",['title' => $event->title]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Event Landing Protagonist'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-landing-protagonist-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
