<?php
$this->title = \amos\newsletter\Module::t('amosnewsletter', 'Templates');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,


])?>

<?= \open20\amos\core\views\AmosLinkPager::widget([
'pagination' => $pagination,
'showSummary' => true,
'bottomPositionSummary' => true,
])?>
