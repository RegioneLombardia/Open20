<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\first-access-wizard
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfileAgeGroup;
use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use yii\helpers\ArrayHelper;
use open20\amos\admin\base\ConfigurationManager;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 * @var \open20\amos\admin\models\UserProfile $facilitatorUserProfile
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

$js = <<<JS
    $('#change-facilitator-button, #insert-facilitator-button').click(function(e){
        e.preventDefault();
        var action = $('#first-access-wizard-form').attr('action');
        if(action.indexOf("?") > -1){
            action += '&';
        }
        else {
           action += '?';
        }
        action = action + 'gotoFacilitator=1';
        console.log(action);
        $('#first-access-wizard-form').attr('action', action);
        $('#first-access-wizard-form').submit();
    });
JS;

$this->registerJs($js);
?>

<div class="first-access-wizard-introducing-myself">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'first-access-wizard-form',
            'class' => 'form',
            'enctype' => 'multipart/form-data', //to load images
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    <?= $form->errorSummary($model, ['class' => 'alert-danger alert fade in', 'role' => 'alert']); ?>
    <section>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-xs-12">
                <h2>
                    <?= $model->getNomeCognome(); ?>
                </h2>
            </div>
        </div>
        <div class="row avatar presentation">
            <div class="col-md-3 col-xs-12">
                <?= $form->field($model, 'userProfileImage')->widget(AttachmentsInput::classname(), [
                    'options' => [
                        'multiple' => false
                    ],
                    'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                        'maxFileCount' => 1, // Client max files,
                        'showRemove' => false,
                        'indicatorNew' => false,
                        'allowedPreviewTypes' => ['image'],
                        'previewFileIconSettings' => false,
                        'overwriteInitial' => false,
                        'layoutTemplates' => false
                    ]
                ])->label(AmosAdmin::t('amosadmin', 'Immagine del profilo'));
                ?>
            </div>
            <div class="col-md-9 col-xs-12 ">
                <?= $form->field($model, 'presentazione_breve')->limitedCharsTextArea([
                    'rows' => 6,
                    'readonly' => false,
                    'placeholder' => AmosAdmin::t('amosadmin', '#short_presentation_placeholder'),
                    'maxlength' => 140
                ])->label(AmosAdmin::t('amosadmin', '#short_presentation'), ['class' => 'bold']); ?>
            </div>
        </div>
    </section>
    <hr>
    <section>
        <div class="row sex agerange">
            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'sesso', [
                    'template' => "{label}\n{hint}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                ])->widget(Select::classname(), [
                    'options' => ['placeholder' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...', 'disabled' => false],
                    'data' => [
                        'None' => AmosAdmin::t('amosadmin', 'Non dichiarato'),
                        'Maschio' => AmosAdmin::t('amosadmin', 'Maschio'),
                        'Femmina' => AmosAdmin::t('amosadmin', 'Femmina')
                    ]
                ])->hint($model->getAttributeHint('sesso'), ['class' => 'text-danger clearfix bold']); ?>
            </div>
            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'user_profile_age_group_id', [
                    'template' => "{label}\n{hint}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                ])->widget(Select::classname(), [
                    'options' => ['placeholder' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...', 'disabled' => false],
                    'data' => ArrayHelper::map(UserProfileAgeGroup::find()->orderBy(['id' => SORT_ASC])->asArray()->all(), 'id', 'age_group')
                ])->label($model->getAttributeLabel('age_group'))->hint($model->getAttributeHint('age_group'), ['class' => 'text-danger clearfix bold']); ?>
            </div>
        </div>
    </section>
    <?php if (($adminModule->confManager->isVisibleBox('box_facilitatori', ConfigurationManager::VIEW_TYPE_FORM)) && ($adminModule->confManager->isVisibleField('facilitatore_id', ConfigurationManager::VIEW_TYPE_FORM))): ?>
    <section>
        <div class="col-xs-12 facilitator-content">
            <div class="col-xs-12 facilitator-textarea">
                <h4><strong><?= AmosAdmin::t('amosadmin', 'The facilitator') ?></strong></h4>
                <p><?= AmosAdmin::t('amosadmin', 'The facilitator is a user with an in-depth knowledge of the platform\'s objectives and methodology and is responsible for providing assistance to users.') ?></p>
                <p><?= AmosAdmin::t('amosadmin', 'You can contact the facilitator at any time for informations on compiling your profile data and using the platform.') ?></p>
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-12">
                <div class="col-md-6 facilitator-id m-t-15 nop">
                    <?php if (!is_null($facilitatorUserProfile)): ?>
                        <div class="col-xs-3 img-profile">
                            <?php
                            $url = $facilitatorUserProfile->getAvatarUrl('original', [
                                'class' => 'img-responsive'
                            ]);
                            Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
                            try {
                                $getHorizontalImageClass = Yii::$app->imageUtility->getHorizontalImage($facilitatorUserProfile->userProfileImage)['class'];
                                $getHorizontalImageMarginLeft = 'margin-left:' . Yii::$app->imageUtility->getHorizontalImage($facilitatorUserProfile->userProfileImage)["margin-left"] . 'px;margin-top:' . Yii::$app->imageUtility->getHorizontalImage($facilitatorUserProfile->userProfileImage)["margin-top"] . 'px;';
                            } catch (\Exception $ex) {
                                $getHorizontalImageClass = '';
                                $getHorizontalImageMarginLeft = '';
                            }
                            ?>
                            <?= Html::img($url, [
                                'class' => 'img-responsive ' . $getHorizontalImageClass,
                                'style' => $getHorizontalImageMarginLeft,
                                'alt' => AmosAdmin::t('amosadmin', 'Profile Image')
                            ]);
                            ?>
                        </div>
                        <div class="col-xs-9 m-t-10">
                            <p><strong><?= $facilitatorUserProfile->getNomeCognome() ?></strong></p>
                            <div><?= AmosAdmin::t('amosadmin', 'Prevalent partnership') . ': ' . (!is_null($facilitatorUserProfile->prevalentPartnership) ? $facilitatorUserProfile->prevalentPartnership->name : '-') ?></div>
                            <?= Html::a(AmosAdmin::t('amosadmin', 'Change facilitator'), ['/'.AmosAdmin::getModuleName().'/first-access-wizard/associate-facilitator', 'id' => $model->id, 'viewM2MWidgetGenericSearch' => true], ['id' => 'change-facilitator-button']) ?>
                        </div>
                    <?php else: ?>
                        <div class="col-xs-9 m-t-10">
                            <div><?= AmosAdmin::tHtml('amosadmin', 'Facilitator not selected') ?></div>
                            <?= Html::a(AmosAdmin::t('amosadmin', 'Change facilitator'), ['/'.AmosAdmin::getModuleName().'/first-access-wizard/associate-facilitator', 'id' => $model->id, 'viewM2MWidgetGenericSearch' => true], ['id' => 'insert-facilitator-button']) ?>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-6 m-t-15">
                    <div class="col-xs-1 nop text-right">
                        <?= AmosIcons::show('info') ?>
                    </div>
                    <div class="col-xs-11">
                        <?= AmosAdmin::t('amosadmin', 'The platform has automatically assigned you a facilitator, but if you wish, you can change it by choosing a person who knows your professional identity and can help you to value it with suggestions and changes to the informations you entered.') ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>


    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/'.AmosAdmin::getModuleName().'/first-access-wizard/introduction'])
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
