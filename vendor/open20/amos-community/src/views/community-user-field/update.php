<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-community/src/views 
 */
/**
* @var yii\web\View $this
* @var open20\amos\community\models\CommunityUserField $model
*/

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Community User Field',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/community']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Community User Field'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="community-user-field-update">

    <?= $this->render('_form', [
    'community' => $community,
    'model' => $model,
    'dataProviderDefaultVals' => $dataProviderDefaultVals,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
