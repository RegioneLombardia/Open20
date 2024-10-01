<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-partnership-profiles/src/views 
 */
/**
* @var yii\web\View $this
* @var open20\amos\partnershipprofiles\models\PartnershipProfilesCategory $model
*/

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Partnership Profiles Category',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/partnershipprofiles']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Partnership Profiles Category'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="partnership-profiles-category-update">

    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
