<?php
use amos\newsletter\Module;

$this->title = \amos\newsletter\Module::t('amosnewsletter', 'Recipient Details');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="col-xs-12">
    <h3><?= \amos\newsletter\Module::t('amosnewsletter', 'Details') ?></h3>

    <?php echo \yii\widgets\DetailView::widget([
        'model' => $recipient,
    ]); ?>
</div>


<div class="col-xs-12">
    <?php
    echo \yii\helpers\Html::a(Module::t('amosnewsletter', 'Back'), \Yii::$app->request->referrer ? \Yii::$app->request->referrer : \yii\helpers\Url::previous(), ['class' => 'btn btn-primary']);
    ?>
</div>
