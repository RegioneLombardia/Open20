<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/socialwall/views
 */
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use open20\amos\core\forms\TextEditorWidget;
use yii\helpers\Inflector;

/**
 * @var yii\web\View $this
 * @var open20\socialwall\models\SocialwallPostsProfiles $model
 * @var yii\widgets\ActiveForm $form
 */

$socialLogo = "";
switch ($model->type) {
    case \open20\socialwall\utility\Social::FACEBOOK:
        $socialLogo = '<span class="icon icon-lg icon-white mdi mdi-facebook" style="font-size: 40px;"></span>';
        break;
    case \open20\socialwall\utility\Social::INSTAGRAM:
        $socialLogo = '<span class="icon icon-lg icon-white mdi mdi-instagram" style="font-size: 40px;"></span>';
        break;
    case \open20\socialwall\utility\Social::TWITTER:
        $socialLogo = '<span class="icon icon-lg icon-white mdi mdi-twitter" style="font-size: 40px;"></span>';
        break;
}


?>
<div class="socialwall-posts-profiles-form col-xs-12 nop">

    <?php     $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'id' => 'socialwall-posts-profiles_' . ((isset($fid))? $fid : 0),
            'data-fid' => (isset($fid))? $fid : 0,
            'data-field' => ((isset($dataField))? $dataField : ''),
            'data-entity' => ((isset($dataEntity))? $dataEntity : ''),
            'class' => ((isset($class))? $class : ''),
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="col-xs-12">
            <h6 class="text-uppercase"><a href="<?= Url::previous(); ?>" title="<?= \open20\socialwall\Module::t('socialwall', 'Torna al contenuto'); ?>"><strong>&#171; <?= \open20\socialwall\Module::t('socialwall', 'Torna al contenuto'); ?></strong></a></h6>

            <div class="form-group">
                <label class="control-label"><?= \open20\socialwall\Module::t('socialwall', 'Tipo profilo') ?></label>
                <div style="display: flex; align-items: center;"><?= $socialLogo; ?><div class="m-l-5"><?= \open20\socialwall\utility\Social::SOCIAL_LABELS[$model->type]; ?></div></div>
            </div>

<!--            --><?php //= $form->field($model, 'profile_picture')->widget(\open20\amos\attachments\components\AttachmentsInput::classname(), [
//                'model' => $model,
//                'options' => [ // Options of the Kartik's FileInput widget
//                    'multiple' => false, // If you want to allow multiple upload, default to false
//                ],
//                'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
//                    'showPreview' => true,
//                    'maxFileCount' => 1 // Client max files
//                ]
//            ]) ?>
<!--            <img src="--><?php //= $model->profile_picture->getUrl(); ?><!--" />-->

            <?=
            $form->field($model, 'profile_picture')->widget(\open20\amos\attachments\components\CropInput::class, [
                'hidePreviewDeleteButton' => false,
                'jcropOptions' => ['aspectRatio' => '1']
            ])
            ?>

            <?= $form->field($model, 'profile_name')->textInput(['maxlength' => true]) ?><!-- profile_url string -->

            <?= $form->field($model, 'profile_url')->textInput(['maxlength' => true]) ?>



            <?= RequiredFieldsTipWidget::widget(); ?>

            <?= CloseSaveButtonWidget::widget(['model' => $model, 'closeButtonLabel' => \open20\socialwall\Module::t('socialwall', 'Chiudi')]); ?>

            <?php ActiveForm::end(); ?>
            <div class="col-md-4 col xs-12"></div>
        </div>
        <div class="clearfix"></div>

    </div>
</div>
