<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use amos\sitemanagement\Module;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementLanding $model
 * @var yii\widgets\ActiveForm $form
 */


?>

<div class="site-management-landing-form col-xs-12 nop">

    <?php $form = ActiveForm::begin(); ?>




    <?php $this->beginBlock('general'); ?>

    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>



    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'view_path')->textInput(['maxlength' => true])->label(Module::t('amossistemanagement', 'View path or url action')) ?>
    </div>

    <div class="col-lg-6 col-sm-6">
        <?php echo $form->field($model, 'layout_path')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-12 col-sm-12">
        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    </div>
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
