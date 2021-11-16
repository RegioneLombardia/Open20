<?php
$this->title = \amos\newsletter\Module::t('amosnewsletter', 'Fields');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
])?>


