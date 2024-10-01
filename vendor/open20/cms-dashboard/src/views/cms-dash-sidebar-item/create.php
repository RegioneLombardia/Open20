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

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Cms Dash Sidebar Item',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Cms Dash Sidebar Item'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-dash-sidebar-item-create">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
        'roles' => $roles
    ]) ?>

</div>
