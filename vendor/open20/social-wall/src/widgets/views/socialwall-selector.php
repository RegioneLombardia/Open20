<?php

open20\socialwall\assets\ModuleSocialWallAsset::register($this);

//$socialTemplates = [
//  "instagram" => $this->render('@vendor/open20/social-wall/src/widgets/views/jsTemplates/instagram_detail'),
//  "twitter" => $this->render('@vendor/open20/social-wall/src/widgets/views/jsTemplates/twitter_detail'),
//];

//$socialwallModel = new \open20\socialwall\models\Socialwall();
$typeLiveSocialwall = \open20\socialwall\utility\Social::LIVE_WALL;
$typePickedPostsSocialwall = \open20\socialwall\utility\Social::PICKED_POSTS_WALL;

?>

<div class="row">
    <div class="col-xs-12">
        <?= $form->field($socialwallModel, 'socialwallType')->widget(\open20\amos\core\forms\editors\Select::class, [
            'auto_fill' => true,
            'data' => \open20\socialwall\utility\Social::enabledSocialwallTypesList(),
            'hideSearch' => true,
            'options' => [
                'id' => $inputId . "-socialwall-type-selector",
                'prompt' => \open20\socialwall\Module::t('socialwall', 'Seleziona'),
                'onchange' => "
                    if($(this).val() == '{$typeLiveSocialwall}') { $(\"#{$inputId}-type-live\").show(); $(\"#{$inputId}-type-picked-posts\").hide(); }
                    if($(this).val() == '{$typePickedPostsSocialwall}') { $(\"#{$inputId}-type-live\").hide(); $(\"#{$inputId}-type-picked-posts\").show(); }
                    if($(this).val() == '') { $(\"#{$inputId}-type-live\").hide(); $(\"#{$inputId}-type-picked-posts\").hide(); }
                "
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(\open20\socialwall\Module::t('socialwall','Tipo visualizzazione'))->hint(\open20\socialwall\Module::t('socialwall','Seleziona il tipo di visualizzazione')); ?>
    </div>
</div>

<div id="<?= $inputId ?>-type-live" class="row" style="display: none;">
    <div class="col-xs-12">
        <?= \open20\socialwall\widgets\SocialwallLiveSelectorWidget::widget([
            'form' => $form,
            'model' => $socialwallModel,
            'inputId' => $inputId,
        ]); ?>
    </div>
</div>

<div id="<?= $inputId ?>-type-picked-posts" class="row" style="display: none;">
    <div class="col-xs-12">
        <?= \open20\socialwall\widgets\SocialwallPickedPostsSelectorWidget::widget([
            'form' => $form,
            'model' => $model,
            'socialwallModel' => $socialwallModel,
            'inputId' => $inputId,
            'parentContainerId' => $inputId . '-type-picked-posts',
        ]); ?>
    </div>
</div>

<?php
$this->registerJs(<<<JS
if($("#{$inputId}-socialwall-type-selector").val() == '{$typeLiveSocialwall}') { $("#{$inputId}-type-live").show(); }
if($("#{$inputId}-socialwall-type-selector").val() == '{$typePickedPostsSocialwall}') { $("#{$inputId}-type-picked-posts").show(); }
JS
);
?>