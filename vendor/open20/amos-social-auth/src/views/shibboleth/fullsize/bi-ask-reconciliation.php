<?php

use open20\amos\admin\AmosAdmin;
use open20\amos\socialauth\Module;
use yii\helpers\Html;

/**
 * @var array $userDatas
 * @var string $authType
 * @var array $registerLink
 * @var array $loginLink
 */

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::getInstance();
$adminModuleName = \open20\amos\admin\AmosAdmin::getModuleName();


$loginWithSpidOrCnsString = Module::t('amossocialauth', 'spid_signup_already_registered2');
if ($authType == 'IDPC_AUTHENTICATION_SMARTCARD') {
    $loginWithSpidOrCnsString = Module::t('amossocialauth', 'spid_signup_already_registered3');
} elseif (($authType == 'IDPC_SPID_L1') || ($authType == 'IDPC_SPID_L2') || ($authType == 'IDPC_SPID_L3')) {
    $loginWithSpidOrCnsString = Module::t('amossocialauth', 'spid_signup_already_registered2');
}

/** @var Module $socialAuthModule */
$socialAuthModule = Module::instance();
$spidSignupSubtitle = (($socialAuthModule->checkOnlyFiscalCode === false) ?
    Module::t('amossocialauth', 'spid_signup_subtitle', ['cf' => $userDatas['codiceFiscale'], 'email' => $userDatas['emailAddress']]) :
    Module::t('amossocialauth', 'spid_signup_subtitle_only_cf', ['cf' => $userDatas['codiceFiscale']])
);

$registerLink = [\open20\amos\socialauth\utility\SocialAuthUtility::getRegisterLink(), 'confirm' => true, 'from-shibboleth' => true];
$loginLink = [\open20\amos\socialauth\utility\SocialAuthUtility::getLoginLink(), 'confirm' => true, 'reconciliation' => true];
?>

<div class="m-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-md text-center text-md-left px-5 pb-5">
                <h2 class=""><?= Module::t('amossocialauth', 'spid_signup_welcome', ['nome' => $userDatas['nome'], 'cognome' => $userDatas['cognome']]) ?></h2>
                <h4 class="title-login"><?= Module::t('amossocialauth', "Grazie di aver effettuato l'accesso con la tua identità digitale. Se hai un’utenza già registrata su Open Innovation, clicca per riconciliare il tuo profilo esistente con l'Identità Digitale. Se invece sei un nuovo utente, finalizza la registrazione", ['cf' => $userDatas['codiceFiscale']]) ?></h4>            </div>
            <div class="col-md text-center text-md-left px-5">
                <div>
                    <p><strong><?= Module::t('amossocialauth', 'Sei già registrato alla piattaforma?') ?></strong></p>
                    <p><?=  Module::t('amossocialauth', 'Clicca qui per riconciliare il tuo profilo') ?></p>
                    <?= Html::a(Module::t('amossocialauth', 'Riconcilia il tuo profilo'), $loginLink, ['class' => 'btn btn-icon rounded-0 btn-primary text-uppercase']); ?>
                </div>
                <div class="mt-4">
                    <p class="py-3" ><strong><?= Module::t('amossocialauth', 'Sei un nuovo utente?') ?></strong></p>
                    <p><?= Module::t('amossocialauth', 'Clicca qui per finalizzare la registrazione') ?></p>
                    <hr>
                    <?= Html::a(Module::t('amossocialauth', 'spid_signup_register_btn'), $registerLink, ['class' => 'btn btn-icon rounded-0 btn-primary text-uppercase']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
