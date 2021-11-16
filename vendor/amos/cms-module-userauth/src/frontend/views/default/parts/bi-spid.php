<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @licence GPLv3
 * @licence https://opensource.org/proscriptions/gpl-3.0.html GNU General Public Proscription version 3
 *
 * @package amos-admin
 * @category CategoryName
 */

use amos\userauth\frontend\Module;;

use open20\amos\core\helpers\Html;
use yii\helpers\Url;

/**
 * @var $socialAuthModule \open20\amos\socialauth\Module
 */
$socialAuthModule = Yii::$app->getModule('socialauth');

?>
<?php if ($socialAuthModule && $socialAuthModule->enableSpid) : ?>

    <?=
        Html::a(
            // '<svg class="icon icon-white icon-rounded"><use xlink:href="' . $currentAsset->baseUrl . '/node_modules//bootstrap-italia/dist/svg/sprite.svg#it-facebook"></use></svg>' .
            // '<img class="icon icon-white icon-rounded" src="' . $currentAsset->baseUrl . '/img/social/icon-spid.svg" alt="SPID logo">' .
                Html::tag('span', Module::t('Entra con SPID')),
            Url::to('/socialauth/shibboleth/endpoint', 'https'),
            [
                'class' => 'btn btn-icon rounded-0 btn-spid text-uppercase',
                'title' => Module::t('Esegui l\'accesso utilizzando SPID'),
            ]
        )
    ?>
    <?php if (!$hideSpidButtonDescription) : ?>
        <p class="mt-3 small text-muted"><?= Module::t('SPID è il sistema di accesso che consente di utilizzare, con un\'identità digitale unica, i servizi online della Pubblica Amministrazione e dei privati accreditati.
Se sei già in possesso di un\'identità digitale, accedi con le credenziali del tuo gestore. Se non hai ancora un\'identità digitale, richiedila ad uno dei gestori.') ?></p>
    <?php endif ?>

<?php endif ?>