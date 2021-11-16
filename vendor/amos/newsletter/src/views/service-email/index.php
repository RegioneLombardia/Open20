<?php
$this->title = \amos\newsletter\Module::t('amosnewsletter', 'Lists');
$this->params['breadcrumbs'][] = $this->title;
$module = \Yii::$app->getModule('newsletter');
?>

<?= \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'IdList',
            'label' => \amos\newsletter\Module::t('amosnewsletter', 'Id')
        ],
        [
            'attribute' => 'Name',
            'label' => \amos\newsletter\Module::t('amosnewsletter', 'Name')
        ],
        [
            'attribute' => 'Company',
            'label' => \amos\newsletter\Module::t('amosnewsletter', 'Company')
        ],
        [
            'attribute' => 'Description',
            'label' => \amos\newsletter\Module::t('amosnewsletter', 'Description')
        ],
        [
            'class' => \open20\amos\core\views\grid\ActionColumn::className(),
            'template' => '{templates}{index-groups}{subscribers}',
            'buttons' => [
                'templates' => function($url, $model) use ($module){
                        return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('view-web'),
                            ['/newsletter/service-email/email-templates', 'idList' => $model->IdList],
                            [
                                'class' => 'btn btn-navigation-primary',
                                'title' => \amos\newsletter\Module::t('amosnewsletter', 'Templates')

                            ]);
                    return '';
                },
                'index-groups' => function($url, $model) use ($module){
                    if($module->enableServiceMailGroups) {
                        return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('ungroup'),
                            ['/newsletter/service-email/index-groups', 'idList' => $model->IdList],
                            [
                                'class' => 'btn btn-navigation-primary',
                                'title' => \amos\newsletter\Module::t('amosnewsletter', 'Groups')

                            ]);
                    }
                    return '';
                },
                'subscribers' => function($url, $model){
                    return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('accounts'),
                        ['/newsletter/service-email/subscribers-list', 'idList' => $model->IdList],
                        [
                            'class' => 'btn btn-navigation-primary',
                            'title' => \amos\newsletter\Module::t('amosnewsletter', 'Subscribers')
                        ]);
                }
            ]
        ]

    ],

])?>

<?= \open20\amos\core\views\AmosLinkPager::widget([
    'pagination' => $pagination,
    'showSummary' => true,
    'bottomPositionSummary' => true,
])?>