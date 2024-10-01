<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/cms-dashboard/src/views 
 */
/**
* @var yii\web\View $this
* @var open20\cms\dashboard\models\CmsDashSidebarItem $model
*/

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Cms Dash Sidebar Item',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Cms Dash Sidebar Item'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="cms-dash-sidebar-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
        'roles' => $roles
    ]) ?>

</div>
