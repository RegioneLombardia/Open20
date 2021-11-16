<?php

use \open20\amos\events\AmosEvents;

$this->title = \Yii::t('amosuserimporter', 'Statistics');
$this->params['breadcrumbs'][] = $model->name; ?>
<div class="col-md-12">
    <p><strong><?= \Yii::t('amosuserimporter','Importazione').': '?></strong><?= $model->name ?></p>
    <h4><?= \Yii::t('amosuserimporter', 'Email statistics')?></h4>

    <?php echo \yii\widgets\DetailView::widget([
        'model' => $counts,
        'attributes' => [
//            [
//                'attribute' => 'idmessage',
//                'label' => Module::t('amosnewsletter', 'Id Message')
//            ],
            [
                'attribute' => 'opened',
                'label' => \Yii::t('amosuserimporter', 'Opened')
            ],
            [
                'attribute' => 'clicks',
                'label' => \Yii::t('amosuserimporter', 'Clicks')
            ],
            [
                'attribute' => 'bounces',
                'label' => \Yii::t('amosuserimporter', 'Bounces')
            ],
            [
                'attribute' => 'unsubscribed',
                'label' => \Yii::t('amosuserimporter', 'Unsubscribed')
            ],
        ]

    ]); ?>
</div>
<hr class="dashed">

<div class="col-md-12">
    <h4><?= \Yii::t('amosuserimporter', 'Clicked links') ?></h4>
    <?php
    echo \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'Count',
                'label' => \Yii::t('amosuserimporter', 'Count')
            ],
            [
                'attribute' => 'Url',
                'label' => \Yii::t('amosuserimporter', 'Clicked url')
            ],
        ]


    ]);
    ?>
</div>

<hr class="dashed">
<div class="col-md-12">
    <h4><?= \Yii::t('amosuserimporter', 'Sending info') ?></h4>
    <?= \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProviderHistory,
        'columns' => [
            [
                'attribute' => 'Id',
                'label' => \Yii::t('amosuserimporter', 'Sending  Id'),
            ],
//            'idMessage',
            [
                'attribute' => 'SenderName',
                'label' => \Yii::t('amosuserimporter', 'Sender name')
            ],
            [
                'attribute' => 'SenderEmail',
                'label' => \Yii::t('amosuserimporter', 'Sender email')
            ],
            [
                'attribute' => 'Recipients',
                'label' => \Yii::t('amosuserimporter', 'Sended to')
            ],
            [
                'attribute' => 'StartDate',
                'label' => \Yii::t('amosuserimporter', 'Sending date'),
                'value' => function ($model) {
                    if ($model->StartDate) {
                        $date = new \DateTime($model->StartDate);
                        return $date->format('d/m/Y H:i:s');
                    }
                },
            ],
            [
                'attribute' => 'EndDate',
                'label' => \Yii::t('amosuserimporter', 'Sending end date'),
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
    <h5><?= \Yii::t('amosuserimporter', 'Sended to') ?></h5>
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
    echo \yii\helpers\Html::a(\Yii::t('amosuserimporter', 'Back'), ['index'], ['class' => 'btn btn-primary']);
    ?>
</div>
