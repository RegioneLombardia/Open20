<?php
use open20\amos\cwh\AmosCwh;

/**
 *
 * @var $this \yii\web\View
 * @var $Network \open20\amos\cwh\models\CwhConfigContents
 */

$this->title = AmosCwh::t('wizard', 'Configurazione {network} del progetto {appName}', [
    'appName' => Yii::$app->name,
    'network' => $Network->tablename
]);

?>


<div class="">
    <?php
    \yii\bootstrap\Alert::begin([
        'closeButton' => false,
        'options' => [
            'class' => 'alert alert-info',
        ],
    ]);
    ?>
    <p>
        <?= AmosCwh::t('wizard', 'Benvenuto nella configurazione di <strong>{contents}</strong>', [
            'contents' => $Network->tablename
        ]) ?>
    </p>
    <?php
    \yii\bootstrap\Alert::end();
    ?>

</div>
<div class="">
    <?php $form = \open20\amos\core\forms\ActiveForm::begin() ?>

    <div class="col-sm-6">
        <?= $form->field($Network, 'classname') ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($Network, 'tablename') ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($Network, 'visibility') ?>
    </div>
    <div class="col-sm-6">
        <?= AmosCwh::t('amoscwh', '#visibility_hint') ?>
    </div>
<!--    <div class="col-sm-12">-->
<!--        < ?= $form->field($Network, 'raw_sql')->textarea([-->
<!--            'rows' => 12-->
<!--        ]) ?>-->
<!--    </div>-->

    <hr />

    <div class="col-sm-12 ">
        <?= \open20\amos\core\helpers\Html::a(AmosCwh::t('amoscwh', 'Chiudi'),\yii\helpers\Url::previous(), [
            'class' => 'btn btn-secondary pull-left m-t-15',
            'name' => 'close',
        ]) ?>
        <?= \open20\amos\core\forms\CloseSaveButtonWidget::widget([
            'model' => $Network,
            'buttonSaveLabel' => AmosCwh::tHtml('amoscwh', 'Salva'),
            'buttonCloseVisibility' => false,
        ])
        ?>
    </div>

    <?php \open20\amos\core\forms\ActiveForm::end() ?>

</div>