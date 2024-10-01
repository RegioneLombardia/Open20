<?php

use open20\amos\comments\assets\CommentsAsset;
use open20\amos\comments\AmosComments;
use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;

$asset = CommentsAsset::register($this);

$labelSigninOrSignup = AmosComments::t('amoscomments', '#beforeActionCtaLoginRegister');
$titleSigninOrSignup = AmosComments::t(
    'amoscomments',
    '#beforeActionCtaLoginRegisterTitle',
    ['platformName' => \Yii::$app->name]
);
$labelSignin = AmosComments::t('amoscomments', '#beforeActionCtaLogin');
$titleSignin = AmosComments::t(
    'amoscomments',
    '#beforeActionCtaLoginTitle',
    ['platformName' => \Yii::$app->name]
);
$subtitleSigninOrSignup = AmosComments::t(
    'amoscomments',
    '#subtitleBoxPartecipaLoginRegister',
    ['platformName' => \Yii::$app->name]
);
$subtitleSignin = AmosComments::t(
    'amoscomments',
    '#subtitleBoxPartecipaLogin',
    ['platformName' => \Yii::$app->name]
);

$labelLink = $labelSigninOrSignup;
$titleLink = $titleSigninOrSignup;
$subtitleBannerCta = $subtitleSigninOrSignup;
$socialAuthModule = Yii::$app->getModule('socialauth');
if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
    $labelLink = $labelSignin;
    $titleLink = $titleSignin;
    $subtitleBannerCta = $subtitleSignin;
}

$ctaLoginRegister = Html::a(
    $labelLink,
    isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
        : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
    [
        'title' => $titleLink,
        'class' => 'btn btn-lg btn-tertiary'
    ]
);

?>
<div class="container">
    <div id="box-partecipa" class="footer_box">
        <div class="uk-container">
            <span class="triangle"></span>
            <div class="row">
                <div class="col-md-9">
                    <p class="h3 m-t-15"><strong><?= AmosComments::t('amoscomments', '#titleBoxPartecipa'); ?></strong></p>
                    <p class="lead"><?= $subtitleBannerCta ?></p>
                </div>
                <div class="col-md-3 text-right m-t-20">
                    <?= $ctaLoginRegister ?>
                </div>
            </div>
        </div>
    </div>
</div>