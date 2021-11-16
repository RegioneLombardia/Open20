<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\mail\user
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \open20\amos\core\user\User $user
 * @var \open20\amos\admin\models\UserProfile $profile
 */

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();

$appLink = Yii::$app->params['platform']['backendUrl'];//Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appFrontLink = Yii::$app->params['platform']['frontendUrl'];//Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appLink = substr($appLink, -1) == '/' ? $appLink : $appLink . '/';
$appLinkPrivacy = Yii::$app->params['platform']['frontendUrl'] . '/it/privacy-policy'; //Yii::$app->urlManager->createAbsoluteUrl(['/'.AmosAdmin::getModuleName().'/user-profile/privacy']);
$appName = Yii::$app->name;

$this->title = AmosAdmin::t('amosadmin', 'Registrazione {appName}', ['appName' => $appName]);
$this->registerCssFile('http://fonts.googleapis.com/css?family=Roboto');

?>

<table style="line-height: 18px;" width=" 600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <div class="corpo"
                 style="padding:10px;margin-bottom:10px;background-color:#ffffff;">

                <div class="sezione" style="overflow:hidden;color:#000000;">
                    <div class="testo">
                        <p style="margin-bottom: 20px;">
                            <span style="font-weight: bold;">

                                Gentile <?=  Html::encode($profile->nome) ?> <?=  Html::encode($profile->cognome) ?>

                                </span>
                            <br/>

                        </p>

                            <p style="margin-bottom: 20px;">
                                sei stato abilitato all'utilizzo del servizio Lombardia Informa.
                            </p>

                            <p style="margin-bottom: 20px;">
                                Clicca <?= Html::a('qui', $appFrontLink) ?> per accedere al servizio.
                            </p>

                            <p style="margin-bottom: 20px;">
                                Una volta effettuato l'accesso, potrai selezionare le tematiche di tuo interesse e completare i tuoi dati nell'area "Il mio profilo"
                            </p>

                        <p style="text-align: right;margin-bottom: 20px">
                            <?= AmosAdmin::t('amosadmin', '#welcome_email_thanks') ?>
                        </p>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>
