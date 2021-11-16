<?php

use amos\sitemanagement\Module;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\Tabs;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementFieldsType $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="site-management-fields-type-form col-xs-12 nop">

    <?php $form = ActiveForm::begin(); ?>

    <?php $this->beginBlock('generale'); ?>

    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'name')->textInput() ?>
    </div>
    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'type')->widget(\kartik\select2\Select2::className(), [
                'data' => ['string' => 'string', 'text' => 'text', 'date' => 'date','html'=>'html' /*'file' => 'file'*/]
            ]) ?>
    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>

    <?php $itemsTab[] = [
        'label' => Module::t('amossitemanagement', 'generale'),
        'content' => $this->blocks['generale'],
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
