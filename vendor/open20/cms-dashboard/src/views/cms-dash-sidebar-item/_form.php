<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/cms-dashboard/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\ArrayHelper;
use \open20\cms\dashboard\Module;
//use yii\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var open20\cms\dashboard\models\CmsDashSidebarItem $model
 * @var yii\widgets\ActiveForm $form
 */

$script = <<< JS
    $(document).on('switchChange.bootstrapSwitch', '#isRolesListActiveBox', function(e){
       console.log($(this).is(':checked'));
       if($(this).is(':checked')) {
           $('#roles_id').prop('disabled', false);
       }
       else {
           $('#roles_id').prop('disabled', true);
       }
    });
JS;
$this->registerJs($script);

if($model->isNewRecord) {
    $position = (\open20\cms\dashboard\models\CmsDashSidebarItem::getCount() + 1);
    $model->rolesListType = 0;
}
else {
    $position = $model->position;
}

$extrapar = [];
if(in_array($model->id, \open20\cms\dashboard\Module::getInstance()->sidebarItemBlacklist)){
    $extrapar = ['disabled' => 'disabled'];
}
?>

<div class="cms-dash-sidebar-item-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'cms-dash-sidebar-item_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="m-t-20">
            <div class="content-form-cmsDashSidebarItem">
                <div class="col-md-12">
                    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'link')->textInput(ArrayHelper::merge(['maxlength' => true], $extrapar)) ?>

                    <?= $form->field($model, 'description')->textInput(['maxlength' => true])->hint("<span class='mdi mdi-information'></span>Testo visibile al passaggio del mouse") ?>

                    <?php $this->beginBlock('shortcut'); ?>

                    <?= $form->field($model, 'link_shortcut')->textInput(ArrayHelper::merge(['maxlength' => true], $extrapar))->hint("<span class='mdi mdi-information'></span>Se impostato, abilita una seconda azione al passaggio del mouse") ?>

                    <?= $form->field($model, 'shortcut_description')->textInput(['maxlength' => true]) ?>

                    <?php $this->endBlock(); ?>

                    <?php /*$form->field($model, 'position')->widget(\kartik\select2\Select2::className(), [
                        'data' => \yii\helpers\ArrayHelper::map(\open20\cms\dashboard\models\CmsDashSidebarItem::find()->all(),
                            'id',
                            function($value){
                                return 'Prima di \''. $value->description . '\'';
                            }),
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'options' => [
                            'multiple' => false,
                            'placeholder' => \Yii::t('amosdashboards', 'Seleziona posizione')
                        ]
                    ])->label(\Yii::t('amosdashboards', 'Posizione')) */?>
                    <?php if($model->isNewRecord) {
                        $model->isVisible = true;
                    } ?>
                    <div class="subcommunity-section m-b-30" style="padding:24px 24px 0 24px">
                        <div class="row">
                            <div class="col-md-10 form-group m-b-0">
                                <?= Html::label(
                                    \open20\cms\dashboard\Module::t('amosdashboards', 'Active'),
                                    'isVisibleItem',
                                    ['class' => 'control-label m-t-10']
                                ); ?>
                            </div>
                            <div class="col-md-2 text-right">
                            <?= $form->field($model, 'isVisible')->widget(\kartik\widgets\SwitchInput::classname(), [
                                'pluginOptions' => [
                                    'size' => 'small',
                                    'onColor' => 'success',
                                    //             'offColor' => 'danger',
                                    'onText' => Module::t('amosdashboards', 'Yes'),
                                    'offText' => Module::t('amosdashboards', 'No'),
                                ],
                                'options' => ['id' => 'isVisibleItem']
                            ])->label(false); ?>
                            </div>
                        </div>
                    </div>
                    <div class="subcommunity-section m-b-30" style="padding:24px 24px 0 24px">
                        <div class="row">
                            <div class="col-md-10 form-group m-b-0">
                                <?= Html::label(
                                    Module::t('amosdashboards', 'Is Target Blank'),
                                    'isTargetBlankItem',
                                    ['class' => 'control-label m-t-10']
                                ); ?>
                            </div>
                            <div class="col-md-2 text-right">
                                <?= $form->field($model, 'isTargetBlank')->widget(\kartik\widgets\SwitchInput::classname(), [
                                    'pluginOptions' => [
                                        'size' => 'small',
                                        'onColor' => 'success',
                                        //             'offColor' => 'danger',
                                        'onText' => Module::t('amosdashboards', 'Yes'),
                                        'offText' => Module::t('amosdashboards', 'No'),
                                    ],
                                    'options' => ['id' => 'isTargetBlankItem']
                                ])->label(false); ?>
                            </div>
                        </div>
                    </div>
                    <!-- < ?= Html::img('/img/set-icone.PNG', [
                        'alt' => 'Elenco icone disponibili'
                        ])
                    ? > -->

                    <?= $form->field($model, 'icon_name')->textInput(['maxlength' => true])
                        ->hint("<span class='mdi mdi-information'></span>Seleziona il nome dell'icona da visualizzare. Si può trovare un elenco di icone più completo: <a href='https://materialdesignicons.com/' target='_blank'>https://materialdesignicons.com/</a>") ?>

                    <div class="subcommunity-section m-b-30" style="padding:24px 24px 0 24px">
                        <div class="row">
                            <div class="col-md-10 form-group m-b-0">
                                <?= Html::label(
                                    Module::t('amosdashboards', 'Activate List'),
                                    'activateListRoles',
                                    ['class' => 'control-label m-t-10']
                                ); ?>
                            </div>
                            <div class="col-md-2 text-right">
                                <?= $form->field($model, 'isRolesListActive')->widget(\kartik\widgets\SwitchInput::classname(), [
                                    'pluginOptions' => [
                                        'size' => 'small',
                                        'onColor' => 'success',
                                        //             'offColor' => 'danger',
                                        'onText' => Module::t('amosdashboards', 'Yes'),
                                        'offText' => Module::t('amosdashboards', 'No'),
                                    ],
                                    'options' => ['id' => 'isRolesListActiveBox']
                                ])->label(false); ?>
                            </div>
                            <div>
                                <div class="col-md-12 form-group m-b-0">
                                    <?= $form->field($model, 'roles_mm')->widget(\kartik\select2\Select2::className(), [
                                        'data' => $roles,
                                        'options' => [
                                            'id' => 'roles_id',
                                            'placeholder' => Module::t('amosdashboards', 'Seleziona Ruoli'),
                                            'multiple' => true,
                                            'disabled' => !$model->isRolesListActive
                                        ]
                                    ]); ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 form-group m-b-0">
                                    <?= Html::label(
                                        Module::t('amosdashboards', 'Type of list'),
                                        'activateListRoles',
                                        ['class' => 'control-label m-t-10']
                                    ); ?>
                                </div>
                                <div class="col-md-12">
                                    <?= $form->field($model, 'rolesListType')->radioList([
                                        '0' => 'Whitelist',
                                        '1' => 'Blacklist'
                                    ],
                                        [
                                            'options' => ['id' => 'rolesListTypeBox']
                                        ])->label(false); ?>
                                </div>
                            </div>
                            </div>

                        </div>
                    </div>

                    <?php $this->beginBlock('avanzate'); ?>
                        <?= $form->field($model, 'id_container')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'class_container')->textInput(['maxlength' => true]) ?>
                    <?php $this->endBlock(); ?>
                </div>
                <div class="col-xs-12">
                <?= $form->field($model, 'position')->hiddenInput(['value' => $position])->label(false); ?>
                <?php $itemsTab = [
                    [
                        'label' => 'Shortcut',
                        'content' => $this->blocks['shortcut'],
                        'active' => true
                    ],
                    [
                        'label' => 'Avanzate',
                        'content' => $this->blocks['avanzate'],
                    ]
                ];
                ?>
                <?= Tabs::widget(
                    [
                        'items' => $itemsTab
                    ]
                ); ?>
                </div>
                <div class="col-xs-12">
                    <?= RequiredFieldsTipWidget::widget(); ?>
                    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-md-4 col xs-12"></div>
            </div>
        </div>
        <!--<div class="clearfix"></div>-->
    </div>
</div>