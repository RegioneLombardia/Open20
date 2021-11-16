<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\TokenGroup $model
 * @var yii\widgets\ActiveForm $form
 */


?>

<div class="token-group-form col-xs-12 nop">

    <?php $form = ActiveForm::begin(); ?>




    <?php $this->beginBlock('general'); ?>

    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'string_code')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-12 col-sm-12">
        <?= $form->field($model, 'Description')->textarea(['rows' => 2, 'maxlength' => true]) ?>
    </div>

    <div class="col-lg- col-sm-6">
        <?= $form->field($model, 'url_redirect')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-4 col-sm-4">
        <?= $form->field($model, 'expire_date')->widget(DateControl::className(),[
            'type' => DateControl::FORMAT_DATETIME
        ]) ?>
    </div>

    <div class="col-lg-2 col-sm-2 m-t-20">
        <?= $form->field($model, 'consumable')->checkbox() ?>
    </div>

    <div class="col-lg-8 col-sm-8">
        <?= $form->field($model, 'target_class')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-4 col-sm-4">
        <?= $form->field($model, 'target_id')->textInput() ?>
    </div>

    <?php if(!$model->isNewRecord){ ?>
        <div class="col-lg-12 col-sm-12">
            <?= \open20\amos\core\views\AmosGridView::widget([
                    'dataProvider' => $dataProvider,
                'columns' => [
                        'user.userProfile.nomeCognome',
                        'token',
                        [
                            'attribute' => 'created_at',
                            'format' => 'date'
                        ]
                ]
            ])?>
        </div>
    <?php } ?>




    <div class="clearfix"></div>
    <?php $this->endBlock('general'); ?>

    <?php $itemsTab[] = [
        'label' => Yii::t('cruds', 'general'),
        'content' => $this->blocks['general'],
    ];
    ?>

    <?= Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]
    );
    ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
