<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo\email
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use open20\amos\invitations\utility\InvitationsUtility;
use open20\amos\organizzazioni\Module;

/**
 * @var yii\web\View $this
 * @var open20\amos\invitations\models\Invitation $invitation
 * @var open20\amos\organizzazioni\models\Profilo $organization
 */

/** @var Module $organizzazioniModule */
$organizzazioniModule = Module::instance();

/** @var User $loggedUser */
$loggedUser = \Yii::$app->user->identity;

/** @var UserProfile $profileSender */
$profileSender = $loggedUser->userProfile;
$adminModuleName = AmosAdmin::getModuleName();
$platformName = Yii::$app->name;

$registerAction = ($invitation->register_action ? $invitation->register_action : '');
$urlConf = [
    \Yii::$app->isBasicAuthEnabled() ? InvitationsUtility::getRegisterLink($registerAction): InvitationsUtility::getLoginLink(),
    'name' => $invitation->name,
    'surname' => $invitation->surname,
    'email' => $invitation->invitationUser->email,
    'iuid' => \Yii::$app->user->id,
    'iid' => $invitation->id,
];

if(!empty($invitation->token)){
    $urlConf['invitation_token'] = $invitation->token;
}

if (!empty($invitation->module_name) && !empty($invitation->context_model_id)) {
    $urlConf['moduleName'] = $invitation->module_name;
    $urlConf['contextModelId'] = $invitation->context_model_id;
}

$url = Yii::$app->urlManager->createAbsoluteUrl($urlConf);

?>
<div>
    <?= Module::t('amosorganizzazioni', '#invite_external_hi') . ' ' . $invitation->getNameSurname() ?>,
</div>

<div style="font-weight: normal">
    <p><?= Module::t('amosorganizzazioni', '#invite_external_text0', [
            'platformName' => $platformName,
            'organizationName' => $organization->name
        ]) ?></p>
    <div style="color:green"><strong><?= $invitation->message ?></strong></div>
    <p style="text-align: center"><a href="<?= $url ?>"><strong><?= Module::t('amosorganizzazioni', '#invite_external_text_registration_page') ?></strong></a></p>
    <?php if ($organizzazioniModule->enableUniqueSecretCodeForInvitation): ?>
        <p><?= Module::t('amosorganizzazioni', "#invite_external_text_secret_code", ['organizationName' => $organization->name]) ?><br><strong><?= $organization->unique_secret_code ?></strong></p>
    <?php endif; ?>
</div>

<div>
    <?= Module::t('amosorganizzazioni', '#invite_external_text_end', ['platformName' => $platformName]) ?>
</div>
