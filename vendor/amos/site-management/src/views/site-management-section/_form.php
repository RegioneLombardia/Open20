<?php

use amos\sitemanagement\Module;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\Tabs;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementSection $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="site-management-section-form col-xs-12 nop">

    <?php $form = ActiveForm::begin(); ?>

    <?php $this->beginBlock('general'); ?>

    <div class="col-lg-6 col-sm-6">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-12 col-sm-12">
        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>

    <?php $itemsTab[] = [
        'label' => Module::t('amossitemanagement', 'general'),
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
