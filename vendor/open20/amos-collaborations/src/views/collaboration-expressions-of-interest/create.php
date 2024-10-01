<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor\open20\amos\collaborations\views/views 
 */
/**
* @var yii\web\View $this
* @var open20\amos\collaborations\models\CollaborationExpressionsOfInterest $model
*/

$this->title = Yii::t('amoscollaborations', 'Manifesta interesse', [
    'modelClass' => 'Collaboration Expressions Of Interest',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/collaborations']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Collaboration Expressions Of Interest'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['forceBreadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Proposte di collaborazione'),  'url' => ['/collaborations/collaboration-proposals/own-interest']];
$this->params['forceBreadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Manifestazioni di interesse'),  'url' => ['/collaborations/collaboration-expressions-of-interest/created-by-me']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
?>
<div class="collaboration-expressions-of-interest-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
