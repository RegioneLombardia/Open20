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
$group = \open20\amos\events\models\EventGroupReferent::findOne($group_id);
$this->title                   = Yii::t('amoscore', 'Aggiungi operatore a').' "'.$group->denominazione.'"';
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Event Group Referent Mm'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-group-referent-mm-create">
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
