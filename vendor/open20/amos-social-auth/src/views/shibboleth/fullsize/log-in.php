<?php

use open20\amos\admin\models\UserProfile;
use open20\amos\socialauth\Module;
use yii\helpers\Html;

/**
 * @var array $userDatas
 * @var $userProfile UserProfile
 * @var string $authType
 */
$module = Yii::$app->getModule('socialauth');
$disableAssociationByEmail = false;
if ($module) {
    $disableAssociationByEmail = $module->disableAssociationByEmail;
}


$subtitleSpidOrCnsString = Module::t('amossocialauth', 'spid_login_subtitle', ['email' => $userDatas['emailAddress']]);
if ($disableAssociationByEmail) {
    $subtitleSpidOrCnsString = Module::t('amossocialauth', 'spid_login_subtitle_no_autoassign', ['email' => $userDatas['emailAddress']]);
}
if ($authType == 'IDPC_AUTHENTICATION_SMARTCARD') {
    $subtitleSpidOrCnsString = Module::t('amossocialauth', 'spid_login_subtitle_cns', ['email' => $userDatas['emailAddress']]);
    if ($disableAssociationByEmail) {
        $subtitleSpidOrCnsString = Module::t('amossocialauth', 'spid_login_subtitle_cns_no_autoassign', ['email' => $userDatas['emailAddress']]);
    }
} elseif (($authType == 'IDPC_SPID_L1') || ($authType == 'IDPC_SPID_L2') || ($authType == 'IDPC_SPID_L3')) {
    $subtitleSpidOrCnsString = Module::t('amossocialauth', 'spid_login_subtitle', ['email' => $userDatas['emailAddress']]);
    if ($disableAssociationByEmail) {
        $subtitleSpidOrCnsString = Module::t('amossocialauth', 'spid_login_subtitle_no_autoassign', ['email' => $userDatas['emailAddress']]);
    }
}

?>

<div class="loginContainerFullsize">
    <div class="login-block social-auth-spid col-xs-12 nop">
        <div class="login-body">
            <h2 class="title-login"><?= Module::t('amossocialauth', 'spid_login_welcome') ?></h2>
            <h3 class="title-login"><?= $subtitleSpidOrCnsString; ?></h3>
            <p class="title-login text-center">
                <strong><?= Module::t('amossocialauth', 'spid_login_user_data') ?></strong></p>
            <p class="title-login text-center"><?= Module::t('amossocialauth', 'spid_login_name') . ': ' . $userProfile->nome ?></p>
            <p class="title-login text-center"><?= Module::t('amossocialauth', 'spid_login_surname') . ': ' . $userProfile->cognome ?></p>
            <p class="title-login text-center"><?= Module::t('amossocialauth', 'spid_login_email') . ': ' . $userProfile->user->email ?></p>
        </div>

        <div class="col-xs-12 action m-t-5">
            <?php if ($disableAssociationByEmail) { ?>
                <?= Html::a(\Yii::t('amossocialauth', 'spid_go_to_login_btn'), ['/admin/user-profile/update', 'id' => $userProfile->id], [
                        'class' => 'btn btn-administration-primary pull-left'
                ]); ?>
            <?php } else { ?>
                <?= Html::a(\Yii::t('amossocialauth', 'spid_login_confirm_btn'), ['/socialauth/shibboleth/endpoint', 'confirm' => true], ['class' => 'btn btn-administration-primary']); ?>
            <?php } ?>
            <?= Html::a(\Yii::t('amossocialauth', 'spid_login_deny_btn'), '/', ['class' => 'btn btn-administration-primary pull-right']); ?>
        </div>
    </div>
</div>
