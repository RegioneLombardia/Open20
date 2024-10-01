<?php

use open20\amos\socialauth\Module;
use yii\helpers\Html;

/**
 * @var array $userDatas
 * @var string $authType
 */

$loginWithSpidOrCnsString = Module::t('amossocialauth', 'spid_signup_already_registered2');
if ($authType == 'IDPC_AUTHENTICATION_SMARTCARD') {
    $loginWithSpidOrCnsString = Module::t('amossocialauth', 'spid_signup_already_registered3');
} elseif (($authType == 'IDPC_SPID_L1') || ($authType == 'IDPC_SPID_L2') || ($authType == 'IDPC_SPID_L3')) {
    $loginWithSpidOrCnsString = Module::t('amossocialauth', 'spid_signup_already_registered2');
}

$adminModuleName = \open20\amos\admin\AmosAdmin::getModuleName();
?>

<div class="loginContainerFullsize">
    <div class="login-block social-auth-spid ask-signup col-xs-12 nop">
        <div class="login-body">
            <h2 class="title-login"><?= Module::t('amossocialauth', 'spid_signup_welcome', ['nome' => $userDatas['nome'], 'cognome' => $userDatas['cognome']]) ?></h2>
            <h3 class="title-login"><?= Module::t('amossocialauth', 'Non è stata trovata nessuna utenza corrispondente al codice fiscale <strong>{cf}</strong>', ['cf' => $userDatas['codiceFiscale']]) ?></h3>
            <hr>
            <div class="action">
                <div>
                    <p><strong><?= Module::t('amossocialauth', 'Sei già registrato alla piattaforma?') ?></strong></p>
                    <p><?=  Module::t('amossocialauth', 'Clicca qui per riconciliare il tuo profilo') ?></p>
                    <?= Html::a(Module::t('amossocialauth', 'Riconcilia il tuo profilo'), ['/'.$adminModuleName.'/security/login', 'reconciliation' => true], ['class' => 'btn btn-administration-primary']); ?>
                </div>
                <div>
                    <p><strong><?= Module::t('amossocialauth', 'Sei un nuovo utente?') ?></strong></p>
                    <p><?= Module::t('amossocialauth', 'clicca qui per finalizzare la registrazione') ?></p>
                    <?= Html::a(Module::t('amossocialauth', 'spid_signup_register_btn'), ['/'.$adminModuleName.'/security/register', 'confirm' => true, 'from-shibboleth' => true], ['class' => 'btn btn-administration-primary']); ?>
                </div>
            </div>
        </div>
    </div>
</div>