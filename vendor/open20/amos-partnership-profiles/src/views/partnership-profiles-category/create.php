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

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Partnership Profiles Category',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/partnershipprofiles']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Partnership Profiles Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partnership-profiles-category-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
