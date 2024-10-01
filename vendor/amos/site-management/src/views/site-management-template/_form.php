<?php

use amos\sitemanagement\Module;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\Tabs;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementTemplate $model
 * @var yii\widgets\ActiveForm $form
 */

$module = \Yii::$app->getModule('sitemanagement');
?>

<div class="site-management-template-form col-xs-12 nop">

    <?php $form = ActiveForm::begin(); ?>

    <?php $this->beginBlock('generale'); ?>

    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'name')->textInput() ?>
    </div>

    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'content_model')->widget(\kartik\select2\Select2::className(),[
            'data' => $module->getAvailableContentModels(),
            'options' => ['placeholder' => Module::t('amossitemanagement', 'Select...')],
            'pluginOptions' => [
                    'allowClear' => true
            ]
        ]) ?>
    </div>


    <div class="col-lg-12 col-sm-12">

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    </div>

    <div class="col-lg-6 col-sm-6">

        <?= $form->field($model, 'view_path')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-6 col-sm-6">
        <?php $model->fieldTypes = $model->siteManagementFieldsTypes;?>
        <?= $form->field($model, 'fieldTypes')->widget(\kartik\select2\Select2::className(),[
            'data' => \yii\helpers\ArrayHelper::map(\amos\sitemanagement\models\SiteManagementFieldsType::find()->all(), 'id', 'name'),
            'pluginOptions' => [
                'multiple' => true
            ]
        ])->label(Module::t('amossitemanagement', 'Field types')) ?>
    </div>

    <div class="col-lg-12 col-sm-12"><h3><?= Module::t('amossitemanagement', 'Fields') ?></h3></div>
    <div class="col-lg-12 col-sm-12">
        <?php
        if (!$model->isNewRecord) {
            $dataProviderFields = new \yii\data\ActiveDataProvider([
                'query' => $model->getSiteManagementFieldsTypes()
            ]);
//            $dataProviderFields = \amos\sitemanagement\models\SiteManagementTemplateFields::
            ?>
            <?php echo \open20\amos\core\views\AmosGridView::widget([
                'dataProvider' => $dataProviderFields,
                'columns' => [
                    'name',
                    'type'
//                            [
//
//                                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
//                                'controller' => 'site-management-container-element-mm',
//                                'template' => '{view}{update}{delete}',
//        //                            'buttons' => [
//        //                                    'view'
//        //                            ]
//                            ]
                ],
            ]);
        }?>
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
