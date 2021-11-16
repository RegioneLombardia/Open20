<?php
use amos\newsletter\Module;
$this->title = \amos\newsletter\Module::t('amosnewsletter', 'Send History');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'Id',
            'label' => Module::t('amosnewsletter', 'Sending  Id'),
        ],
        'idMessage',
        [
            'attribute' => 'SenderName',
            'label' => Module::t('amosnewsletter', 'Sender name')
        ],
        [
            'attribute' => 'SenderEmail',
            'label' => Module::t('amosnewsletter', 'Sender email')
        ],
        [
            'attribute' => 'Recipients',
            'label' => Module::t('amosnewsletter', 'Sended to')
        ],
        [
            'attribute' => 'StartDate',
            'label' => Module::t('amosnewsletter', 'Sending date'),
            'format' => 'datetime'
        ],
    ]


])?>

<?= \open20\amos\core\views\AmosLinkPager::widget([
'pagination' => $pagination,
'showSummary' => true,
'bottomPositionSummary' => true,
])?>
