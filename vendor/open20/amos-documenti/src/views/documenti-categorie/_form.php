<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-categorie
 * @category   CategoryName
 */

use open20\amos\attachments\components\CropInput;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use kartik\widgets\SwitchInput;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\DocumentiCategorie $model
 * @var yii\widgets\ActiveForm $form
 */

$module = \Yii::$app->getModule('documenti');
$enableCategoriesForCommunity = $module->enableCategoriesForCommunity;
$filterCategoriesByRole = $module->filterCategoriesByRole;
$enableAnnualArchiving = $module->enableAnnualArchiving;
$enableCountdownUnpublishing = $module->enableCountdownUnpublishing;

?>

<div class="documenti-categorie-form col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'] // important
    ]);

    $customView = Yii::$app->getViewPath() . '/imageField.php';
    ?>

    <div class="row">
        
        <?php if($enableAnnualArchiving): ?>
            
            <div class="col-xs-12 m-t-10 m-b-10">
                <div class="row" style="background: #f0f0f0;; margin: 5px 0px;">
                    <div class="col-xs-12 col-md-8">
                        <?= Html::tag('label',AmosDocumenti::t('amosdocumenti', '#annual_archiving_document_category'),['class'=>'control-label']) ?>
                        <?= Html::tag('span',AmosDocumenti::t('amosdocumenti', '#annual_archiving_document_category_hint', ['tooltipAnnualArchivingCategory' => '<span class="mdi mdi-information-outline" title="' . AmosDocumenti::t('amosdocumenti', '#tooltip_annual_archiving_category') . '" data-toggle="tooltip"></span>']),['class'=>'help-block']) ?>
                    </div>
                    <div class="col-xs-12 col-md-4 text-right">
                        <?= $form->field($model, 'enable_annual_archiving')->widget(SwitchInput::class, [
                                'options'=>['style'=>'width:100px'],
                                'pluginOptions' => [
                                        'size' => 'medium',
                                        'onColor' => 'success',
                                        'offColor' => 'danger',
                                        'onText' => AmosDocumenti::t('amosdocumenti','#comments_ok'),
                                        'offText' => AmosDocumenti::t('amosdocumenti','#comments_no'),
                                ],
                                'pluginEvents'=>[
                                    "switchChange.bootstrapSwitch" => 'function(event,state) {

                                        if(state){

                                        }else{

                                        }

                                    }',
                                ],
                            ])->label(false); 
                        ?>      
                    </div>
                </div>
            </div>
            
        <?php endif; ?>
        
        <?php if($enableCountdownUnpublishing): ?>
            
            <div class="col-xs-12 m-t-10 m-b-10">
                <div class="row" style="background: #f0f0f0;; margin: 5px 0px;">
                    <div class="col-xs-12 col-md-8">
                        <?= Html::tag('label',AmosDocumenti::t('amosdocumenti', '#countdown_unpublishing_document_category'),['class'=>'control-label']) ?>
                        <?= Html::tag('span',AmosDocumenti::t('amosdocumenti', '#countdown_unpublishing_document_category_hint', ['tooltipCountdownCategory' => '<span class="mdi mdi-information-outline" title="' . AmosDocumenti::t('amosdocumenti', '#tooltip_countdown_unpublishing_category') . '" data-toggle="tooltip"></span>']),['class'=>'help-block']) ?>
                    </div>
                    <div class="col-xs-12 col-md-2 text-right">
                        <?= $form->field($model, 'enable_countdown_unpublishing')->widget(SwitchInput::class, [
                                'options'=>['style'=>'width:100px'],
                                'pluginOptions' => [
                                        'size' => 'medium',
                                        'onColor' => 'success',
                                        'offColor' => 'danger',
                                        'onText' => AmosDocumenti::t('amosdocumenti','#comments_ok'),
                                        'offText' => AmosDocumenti::t('amosdocumenti','#comments_no'),
                                ],
                                'pluginEvents'=>[
                                    "switchChange.bootstrapSwitch" => 'function(event,state) {

                                        if(state){
                                            $("#documenticategorie-years_countdown_unpublishing").attr("readonly", false);
                                        }else{
                                            $("#documenticategorie-years_countdown_unpublishing").attr("readonly", true);
                                        }
                                        
                                        $("#w0").yiiActiveForm("validateAttribute", "documenticategorie-years_countdown_unpublishing");

                                    }',
                                ],
                            ])->label(false); 
                        ?>      
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <?= $form->field($model, 'years_countdown_unpublishing')->textInput(['maxlength' => true, 'placeholder' => '', 'readonly'=>!$model->enable_countdown_unpublishing]) ?>                        
                    </div>
                </div>
            </div>
            
        <?php endif; ?>
        
        <div class="col-xs-12"><?= Html::tag('h2', AmosDocumenti::t('amosdocumenti', '#settings_general_title'), ['class' => 'subtitle-form']) ?></div>
        <div class="col-lg-8 col-xs-12">                      
            <?= $form->field($model, 'titolo')->textInput(['maxlength' => true, 'placeholder' => AmosDocumenti::t('amosdocumenti', '#category_title_placeholder')]) ?>
            <?= $form->field($model, 'sottotitolo')->textInput(['maxlength' => true, 'placeholder' => AmosDocumenti::t('amosdocumenti', '#category_subtitle_placeholder')]) ?>
            <?= $form->field($model, 'descrizione_breve')->textInput(['maxlength' => true, 'placeholder' => AmosDocumenti::t('amosdocumenti', '#category_abstract_placeholder')]) ?>
            <?= $form->field($model, 'descrizione')->textarea(['rows' => 6, 'placeholder' => AmosDocumenti::t('amosdocumenti', '#category_text_placeholder')]) ?>
        </div>
        <div class="col-lg-4 col-xs-12">
            <?= $form->field($model, 'documentCategoryImage')->widget(CropInput::classname(), [
                'jcropOptions' => ['aspectRatio' => '1.7']
            ])->label(AmosDocumenti::t('amosdocumenti', '#category_image_field'))->hint(AmosDocumenti::t('amosdocumenti', '#category_image_field_hint')) ?>
        </div>
    </div>
    <?php if ($filterCategoriesByRole) {
        $whiteRoles = $module->whiteListRolesCategories;
        $roles = array_combine($whiteRoles, $whiteRoles);
        ?>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'documentiCategoryRoles')->widget(\kartik\select2\Select2::className(), [
                    'data' => $roles,
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['multiple' => true, 'placeholder' => AmosDocumenti::t('amosnews', 'Select...')]
                ])->label(AmosDocumenti::t('amosdocumenti', 'Roles')) ?>
            </div>
        </div>
    <?php } ?>

    <div class="clearfix"></div>

    <?php if ($enableCategoriesForCommunity) { ?>
        <hr>
        <h3><?= AmosDocumenti::t('amosdocumenti', 'Configuration for community') ?></h3>
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <?= $form->field($model, 'documentiCategoryCommunities')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\open20\amos\community\models\Community::find()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['multiple' => true, 'placeholder' => AmosDocumenti::t('amosnews', 'Select...')]
                ])->label(AmosDocumenti::t('amosdocumenti', 'Community')) ?>
            </div>
            <div class="col-lg-6 col-sm-12">
                <?= $form->field($model, 'visibleToCommunityRole')->widget(\kartik\select2\Select2::className(), [
                    'data' => [
                        'COMMUNITY_MANAGER' => AmosDocumenti::t('amosdocumenti', 'Community manager'),
                        'PARTICIPANT' => AmosDocumenti::t('amosdocumenti', 'Participant'),
                    ],
                    'options' => [
                        'placeholder' => 'Select...',
                        'multiple' => true

                    ]
                ])->label(AmosDocumenti::t('amosdocumenti', 'Visible to Community roles')); ?>

            </div>
        </div>

    <?php } ?>


    <div class="col-xs-12"><?= CreatedUpdatedWidget::widget(['model' => $model]) ?></div>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
