<?php
$this->title = \amos\newsletter\Module::t('amosnewsletter', 'Groups');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'idGroup',
            'label' => \amos\newsletter\Module::t('amosnewsletter', 'Id')
        ],
        [
            'attribute' => 'Name',
            'label' => \amos\newsletter\Module::t('amosnewsletter', 'Name')
        ],
        [
            'attribute' => 'Notes',
            'label' => \amos\newsletter\Module::t('amosnewsletter', 'Notes')
        ],
        [
            'class' => \open20\amos\core\views\grid\ActionColumn::className(),
            'template' => '{templates}{view}',
            'buttons' => [
                'view' => function($url, $model){

                        return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('accounts'),
                            ['/newsletter/service-email/view-group', 'idGroup' => $model->idGroup],
                            [
                                'class' => 'btn btn-navigation-primary',
                                'title' => \amos\newsletter\Module::t('amosnewsletter', 'Subscribers')

                            ]);
                },
                'templates' => function($url, $model){
                    return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('view-web'),
                        ['/newsletter/service-email/email-templates', 'idList' => $model->idList, 'idGroups' => $model->idGroup],
                        [
                            'class' => 'btn btn-navigation-primary',
                            'title' => \amos\newsletter\Module::t('amosnewsletter', 'Templates')
                        ]);
                    return '';
                },
            ]
        ]

    ],

])?>

<?= \open20\amos\core\views\AmosLinkPager::widget([
'pagination' => $pagination,
'showSummary' => true,
'bottomPositionSummary' => true,
])?>
