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
 * @var open20\socialwall\models\SocialwallPosts $model
 * @var yii\widgets\ActiveForm $form
 */

$rteToolbar = Yii::$app->getModule('socialwall')->rteToolbar;

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
<div class="socialwall-posts-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'id' => 'socialwall-posts_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>

    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label"><?= \open20\socialwall\Module::t('socialwall', 'Profilo') ?></label>
                        <div style="display: flex; align-items: center;">
                            <?= $socialLogo; ?>
                            <?php if (!empty($model->profile_picture)) : ?><img class="m-l-10" src="<?= $model->profile_picture; ?>" width="25px" /><?php endif; ?><a class="m-l-5" href="<?= $model->socialwallPostsProfile->profile_url; ?>" target="_blank"><?= $model->socialwallPostsProfile->profile_name; ?></a>
                            <a class="m-l-30" href="/socialwall/socialwall-posts-profiles/update?id=<?= $model->socialwallPostsProfile->id; ?>"><?= \open20\socialwall\Module::t('socialwall', 'Modifica profilo'); ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label"><?= \open20\socialwall\Module::t('socialwall', 'Permalink') ?></label>
                        <div><a href="<?= $model->permalink; ?>" target="_blank"><?= $model->permalink; ?></a></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <?= $form->field($model, 'post_image')->widget(\open20\amos\attachments\components\CropInput::class, [
                        'hidePreviewDeleteButton' => false,
                        'jcropOptions' => ['aspectRatio' => 'NaN']
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <?php $clientOption = [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        'plugins' => 'paste link',
                        'toolbar' => $rteToolbar,
                    ]; ?>
        <!--            --><?php //= $form->field($model, 'text')->textarea(['maxlength' => true]); ?>
                    <?= $form->field($model, 'text')->widget(
                        TextEditorWidget::class,
                        [
                            'options' => ['placeholder' => \open20\socialwall\Module::t('socialwall', 'Inserisci...')],
                            'clientOptions' => $clientOption,
                        ]); ?>

                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label"><?= \open20\socialwall\Module::t('socialwall', 'Data pubblicazione su social') ?></label>
                        <div><?= Yii::$app->formatter->asDate(
                                strtotime($model->publication_date),
                                isset(
                                    \Yii::$app->components['formatter']['datetimeFormat']) ?
                                    \Yii::$app->components['formatter']['datetimeFormat'] :
                                    'd-m-Y H:i:s'); ?></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <?=
                        $form->field($model, 'search_tags')->widget(\xj\tagit\Tagit::classname(), [
                            'clientOptions' => [
                                'singleField' => true,
                            ],
                        ]);
                    ?>
                </div>
            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-xs-12">
            <?= CloseSaveButtonWidget::widget([
                'model' => $model,
                'closeButtonLabel' => \open20\socialwall\Module::t('socialwall', 'Chiudi'),
                'urlClose' => '/socialwall/socialwall-posts'
            ]); ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
