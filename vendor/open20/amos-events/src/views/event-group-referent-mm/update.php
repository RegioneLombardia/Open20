<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos/events/src/views 
 */
/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventGroupReferentMm $model
 */
$this->title                   = Yii::t('amoscore', 'Aggiorna',
        [
        'modelClass' => 'Event Group Referent Mm',
    ]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Event Group Referent Mm'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="event-group-referent-mm-update">

    <?=
    $this->render('_form',
        [
        'model' => $model,
        'group_id' => $group_id,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ])
    ?>

</div>
