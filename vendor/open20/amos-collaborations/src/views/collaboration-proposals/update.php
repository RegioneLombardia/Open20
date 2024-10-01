<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @open20/amos/collaborations/views/views 
 */
/**
* @var yii\web\View $this
* @var open20\amos\collaborations\models\CollaborationProposals $model
*/

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Collaboration Proposals',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/collaborations']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Collaboration Proposals'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');

$this->params['forceBreadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Proposte di collaborazione'),  'url' => ['/collaborations/collaboration-proposals/own-interest']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
?>

<div class="collaboration-proposals-update">

    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
