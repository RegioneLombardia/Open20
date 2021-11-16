<?php

use amos\newsletter\Module;

$this->title = \amos\newsletter\Module::t('amosnewsletter', 'Templates');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'idList',
            'label' => Module::t('amosnewsletter', 'Id list')
        ],
        [
            'attribute' => 'idMessage',
            'label' => Module::t('amosnewsletter', 'Id message')
        ],
        [
            'attribute' => 'Subject',
            'label' => Module::t('amosnewsletter', 'Subject')
        ],
        [
            'attribute' => 'Notes',
            'label' => Module::t('amosnewsletter', 'Notes')
        ],
        [
            'attribute' => 'CreationDate',
            'format' => 'datetime',
            'label' => Module::t('amosnewsletter', 'Creation Date')

        ],
        [
            'class' => \open20\amos\core\views\grid\ActionColumn::className(),
            'template' => '{statistics}{view}{update}{copy}{send}',
            'buttons' => [
                'view' => function ($url, $model) use ($idGroup) {
                    return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('file'),
                        ['email', 'idList' => $model->idList, 'idMessage' => $model->idMessage, 'idGroup' => $idGroup], [
                            'class' => 'btn btn-tools-secondary',
                            'title' => Module::t('amosnewsletter', "View preview")
                        ]);
                },
                'statistics' => function ($url, $model) use ($idGroup) {
                    return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('bar-chart',[],'dash'),
                        ['email-statistics', 'idMessage' => $model->idMessage, 'idList' => $model->idList], [
                            'class' => 'btn btn-tools-secondary',
                            'title' => Module::t('amosnewsletter', "View statistics")
                        ]);
                },
                'update' => function ($url, $model) {
                    return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('edit'),
                        ['email-update', 'idList' => $model->idList, 'idMessage' => $model->idMessage], [
                            'class' => 'btn btn-tools-secondary',
                            'title' => Module::t('amosnewsletter', "Update")
                        ]);
                },
                'copy' => function ($url, $model) {
                    return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('copy'),
                        ['copy-message', 'idList' => $model->idList, 'idMessage' => $model->idMessage], [
                            'class' => 'btn btn-tools-secondary',
                            'title' => Module::t('amosnewsletter', "Copy"),
                            'data-confirm' => 'Vuoi copiare il messaggio?'
                        ]);
                },
                'send' => function ($url, $model) use ($idGroup) {
                    if ($idGroup) {
                        $title = Module::t('amosnewsletter', 'Send email to group ') . $idGroup;
                        $confirm = Module::t('amosnewsletter', "Are you sure to send the email to all member of the group {group}?", ['group' => $idGroup]);
                    } else {
                        $title = Module::t('amosnewsletter', "Send email to list");
                        $confirm = Module::t('amosnewsletter', "Are you sure to send the email to all member of the list?");
                    }
                    return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('email'),
                        ['send-email-to-list', 'idList' => $model->idList, 'idMessage' => $model->idMessage, 'idGroups' => $idGroup], [
                            'class' => 'btn btn-tools-secondary',
                            'title' => $title,
                            'data-confirm' => $confirm,
                        ]);
                }
            ]
        ]
    ]

]) ?>

<?= \open20\amos\core\views\AmosLinkPager::widget([
    'pagination' => $pagination,
    'showSummary' => true,
    'bottomPositionSummary' => true,
]) ?>
