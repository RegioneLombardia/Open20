
<?php
use amos\newsletter\Module;

$this->title = \amos\newsletter\Module::t('amosnewsletter', 'Recipient statistic');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="col-xs-12">
    <h3><?= \amos\newsletter\Module::t('amosnewsletter', 'Detail Recipient') ?></h3>

    <?php echo \yii\widgets\DetailView::widget([
        'model' => $recipient,
    ]); ?>
</div>

<div class="col-xs-12">
    <h3><?= \amos\newsletter\Module::t('amosnewsletter', 'Email statistics') ?></h3>

    <?php echo \yii\widgets\DetailView::widget([
        'model' => $counts,
        'attributes' => [
            [
                'attribute' => 'idmessage',
                'label' => Module::t('amosnewsletter', 'Id Message')
            ],
            [
                'attribute' => 'deliveries',
                'label' => Module::t('amosnewsletter', 'Deliveries')
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

        ]

    ]); ?>
</div>

<div class="col-xs-12">
    <?php
    echo \yii\helpers\Html::a(Module::t('amosnewsletter', 'Back'), ['email-statistics', 'idMessage' => \Yii::$app->request->get('idMessage'), 'idList' => \Yii::$app->request->get('idList')], ['class' => 'btn btn-primary']);
    ?>
</div>
