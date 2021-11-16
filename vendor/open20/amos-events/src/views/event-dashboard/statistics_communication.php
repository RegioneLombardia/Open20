<?php

use \open20\amos\events\AmosEvents;

$this->title = \open20\amos\events\AmosEvents::t('amosevents', 'Statistics');
$this->params['breadcrumbs'][] = $this->title; ?>
<div class="col-md-12">
    <p><strong><?= AmosEvents::t('amosevents','Evento').': '?></strong><?= $event->title ?></p>
    <p><strong><?= AmosEvents::t('amosevents','Comunicazione').': '?></strong><?= $model->title ?></p>
    <h4><?= AmosEvents::t('amosevents', 'Email statistics')?></h4>

    <?php echo \yii\widgets\DetailView::widget([
        'model' => $counts,
        'attributes' => [
//            [
//                'attribute' => 'idmessage',
//                'label' => Module::t('amosnewsletter', 'Id Message')
//            ],
            [
                'attribute' => 'opened',
                'label' => AmosEvents::t('amosevents', 'Opened')
            ],
            [
                'attribute' => 'clicks',
                'label' => AmosEvents::t('amosevents', 'Clicks')
            ],
            [
                'attribute' => 'bounces',
                'label' => AmosEvents::t('amosevents', 'Bounces')
            ],
            [
                'attribute' => 'unsubscribed',
                'label' => AmosEvents::t('amosevents', 'Unsubscribed')
            ],
        ]

    ]); ?>
</div>
<hr class="dashed">

<div class="col-md-12">
    <h4><?= AmosEvents::t('amosevents', 'Clicked links') ?></h4>
    <?php
    echo \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'Count',
                'label' => AmosEvents::t('amosevents', 'Count')
            ],
            [
                'attribute' => 'Url',
                'label' => AmosEvents::t('amosevents', 'Clicked url')
            ],
        ]


    ]);
    ?>
</div>

<hr class="dashed">
<div class="col-md-12">
    <h4><?= AmosEvents::t('amosevents', 'Sending info') ?></h4>
    <?= \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProviderHistory,
        'columns' => [
            [
                'attribute' => 'Id',
                'label' => AmosEvents::t('amosevents', 'Sending  Id'),
            ],
//            'idMessage',
            [
                'attribute' => 'SenderName',
                'label' => AmosEvents::t('amosevents', 'Sender name')
            ],
            [
                'attribute' => 'SenderEmail',
                'label' => AmosEvents::t('amosevents', 'Sender email')
            ],
            [
                'attribute' => 'Recipients',
                'label' => AmosEvents::t('amosevents', 'Sended to')
            ],
            [
                'attribute' => 'StartDate',
                'label' => AmosEvents::t('amosevents', 'Sending date'),
                'value' => function ($model) {
                    if ($model->StartDate) {
                        $date = new \DateTime($model->StartDate);
                        return $date->format('d/m/Y H:i:s');
                    }
                },
            ],
            [
                'attribute' => 'EndDate',
                'label' => AmosEvents::t('amosevents', 'Sending end date'),
                'value' => function ($model) {
                    if ($model->EndDate) {
                        $date = new \DateTime($model->EndDate);
                        return $date->format('d/m/Y H:i:s');
                    }
                },
            ],
        ]


    ]) ?>
</div>
<hr class="dashed">

<div class="col-md-12">
    <h5><?= AmosEvents::t('amosevents', 'Sended to') ?></h5>
    <?= \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProviderRecipients,
        'columns' => [
//            'IdRecipient',
            'Email',
//            [
//                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
//                'template' => '{statistics}',
//                'buttons' => [
//                    'statistics' => function ($url, $model) {
//                        return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('file'),
//                            [
//                                'recipient-statistics', 'idRecipient' => $model->IdRecipient, 'idMessage' => $model->IdMessage
//                            ],
//                            [
//                                'class' => 'btn btn-tools-secondary',
//                                'title' => Module::t('amosnewsletter', "View recipient statistcs")
//                            ]);
//                    },
//                ]
//            ]
        ]
    ]) ?>
    <?= \open20\amos\core\views\AmosLinkPager::widget([
        'pagination' => $pagination,
        'showSummary' => true,
        'bottomPositionSummary' => true,
    ]) ?>

</div>

<div class="col-md-12">
    <?php
    echo \yii\helpers\Html::a(AmosEvents::t('amosevents', 'Back'), ['communications', 'id' => $event->id], ['class' => 'btn btn-primary']);
    ?>
</div>
