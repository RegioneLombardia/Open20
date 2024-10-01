<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\views\invitation
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use open20\amos\invitations\Module;
use open20\amos\invitations\utility\InvitationsUtility;

$moduleUserAuth = \Yii::$app->getModule('userauthfrontend');
$moduleAdmin = \Yii::$app->getModule(\open20\amos\admin\AmosAdmin::getModuleName());

/**
 * @var yii\web\View $this
 * @var open20\amos\invitations\models\Invitation $invitation
 */

/** @var User $loggedUser */
$loggedUser = \Yii::$app->user->identity;

/** @var UserProfile $profileSender */
$profileSender = $loggedUser->userProfile;
$appName = Yii::$app->name;

$registerAction = ($invitation->register_action ? $invitation->register_action : '');
$urlConf = [
    InvitationsUtility::getRegisterLink($registerAction),
    'name' => $invitation->name,
    'surname' => $invitation->surname,
    'email' => $invitation->invitationUser->email,
    'iuid' => \Yii::$app->user->id,
    'iid' => $invitation->id
];

if(!empty($invitation->token)){
    $urlConf['invitation_token'] = $invitation->token;
}
if (!empty($invitation->module_name) && !empty($invitation->context_model_id)) {
    $urlConf['moduleName'] = $invitation->module_name;
    $urlConf['contextModelId'] = $invitation->context_model_id;
}

$url = Yii::$app->urlManager->createAbsoluteUrl($urlConf);

$skipTextHowto = false;
if ($moduleUserAuth && ($moduleUserAuth->hasProperty('enableDlSemplificationLight') && $moduleUserAuth->enableDlSemplificationLight)) {
    $skipTextHowto = true;
    if (!empty(\Yii::$app->params['linkConfigurations']['loginLinkCommon'])) {
        $redirect = $url;
        $url = Yii::$app->urlManager->createAbsoluteUrl([\Yii::$app->params['linkConfigurations']['loginLinkCommon'], 'redirectToRegisterUrl' => $redirect]);
    }
}

?>
<div>
    <?= Module::t('amosinvitations', '#hi') . ' ' . $invitation->getNameSurname() ?>,
</div>
<div style="font-weight: normal">
    <p><?= Module::t('amosinvitations', '#text_email_invitation0', [
            'platformName' => $appName,
            'sender' => $profileSender->nomeCognome
        ]) ?></p>
    <div><strong><?= $invitation->message ?></strong></div>
    <p style="text-align: center">
        <a href="<?= $url ?>"><strong><?= Module::t('amosinvitations', '#registration_page') ?></strong></a>
    </p>
    <?php if (!$skipTextHowto) { ?>
        <p><?= Module::t('amosinvitations', "#text_email_invitation1", ['platformName' => $appName]) ?></p><br>
    <?php } ?>
    <p>
        <strong><?= Module::t('amosinvitations', '#text_email_invitation2', ['platformName' => $appName]) ?></strong>
    </p>
    <br>
    <p><?= Module::t('amosinvitations', "#text_email_invitation3", ['platformName' => $appName]) ?></p>
</div>

<div>
    <?= Module::t('amosinvitations', '#invitation-email-end', ['site' => $appName]) ?>
</div>