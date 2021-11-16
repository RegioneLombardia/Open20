<?php

use amos\newsletter\Module;

$this->title = \amos\newsletter\Module::t('amosnewsletter', 'Statistics');
$this->params['breadcrumbs'][] = $this->title; ?>
<div class="col-xs-12">
    <h3><?= Module::t('amosnewsletter', 'Email statistics') ?></h3>

    <?php echo \yii\widgets\DetailView::widget([
        'model' => $counts,
        'attributes' => [
            [
                'attribute' => 'idmessage',
                'label' => Module::t('amosnewsletter', 'Id Message')
            ],
            [
                'attribute' => 'opened',
                'label' => Module::t('amosnewsletter', 'Opened')
            ],
            [
                'attribute' => 'clicks',
                'label' => Module::t('amosnewsletter', 'Clicks')
            ],
            [
                'attribute' => 'bounces',
                'label' => Module::t('amosnewsletter', 'Bounces')
            ],
            [
                'attribute' => 'unsubscribed',
                'label' => Module::t('amosnewsletter', 'Unsubscribed')
            ],
        ]

    ]); ?>
</div>

<div class="col-xs-12">
    <h3><?= Module::t('amosnewsletter', 'Clicked links') ?></h3>
    <?php
    echo \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'IdMessage',
                'label' => Module::t('amosnewsletter', 'Id message'),
            ],
            [
                'attribute' => 'Count',
                'label' => Module::t('amosnewsletter', 'Count')
            ],
            [
                'attribute' => 'Url',
                'label' => Module::t('amosnewsletter', 'Clicked url')
            ],
        ]


    ]);
    ?>
</div>

<div class="col-xs-12">
    <h3><?= Module::t('amosnewsletter', 'Sending info') ?></h3>
    <?= \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProviderHistory,
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
                'value' => function ($model) {
                    if ($model->StartDate) {
                        $date = new \DateTime($model->StartDate);
                        return $date->format('d M Y H:i:s');
                    }
                },
            ],
            [
                'attribute' => 'EndDate',
                'label' => Module::t('amosnewsletter', 'Sending end date'),
                'value' => function ($model) {
                    if ($model->EndDate) {
                        $date = new \DateTime($model->EndDate);
                        return $date->format('d M Y H:i:s');
                    }
                },
            ],
        ]


    ]) ?>
</div>

<div class="col-xs-12">
    <h3><?= Module::t('amosnewsletter', 'Sended to') ?></h3>
    <?= \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProviderRecipients,
        'columns' => [
            'IdRecipient',
            'Email',
            [
                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                'template' => '{statistics}',
                'buttons' => [
                    'statistics' => function ($url, $model) {
                        return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('file'),
                            [
                                'recipient-statistics', 'idRecipient' => $model->IdRecipient, 'idMessage' => $model->IdMessage
                            ],
                            [
                                'class' => 'btn btn-tools-secondary',
                                'title' => Module::t('amosnewsletter', "View recipient statistcs")
                            ]);
                    },
                ]
            ]
        ]
    ]) ?>
    <?= \open20\amos\core\views\AmosLinkPager::widget([
        'pagination' => $pagination,
        'showSummary' => true,
        'bottomPositionSummary' => true,
    ]) ?>

</div>

<div class="col-xs-12">
    <?php
    echo \yii\helpers\Html::a(Module::t('amosnewsletter', 'Back'), ['email-templates', 'idList' => \Yii::$app->request->get('idList')], ['class' => 'btn btn-primary']);
    ?>
</div>
