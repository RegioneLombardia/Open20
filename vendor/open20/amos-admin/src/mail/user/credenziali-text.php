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

$appLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appLinkPrivacy = Yii::$app->urlManager->createAbsoluteUrl(['/'.AmosAdmin::getModuleName().'/user-profile/privacy']);
$appName = Yii::$app->name;

?>
<?= AmosAdmin::tHtml('amosadmin', 'Gentile {nome} {cognome},', [
    'nome' => Html::encode($profile->nome),
    'cognome' => Html::encode($profile->cognome)]);
?>
<?= "\n"; ?>
<?= AmosAdmin::tHtml('amosadmin', 'la presente per comunicarle, ai sensi dell’art. 6 dell’AVVISO PUBBLICO “Presentazione domande volte all’inserimento nel Registro dei formatori del Sistema della IeFP della Regione Emilia Romagna, per l’eventuale conferimento di incarichi non a carattere subordinato”, che la sua domanda di inserimento nel Registro dei Formatori risulta formalmente corretta e pertanto accoglibile.\n\nDi seguito troverà le credenziali per accedere alla Piattaforma collaborativa dei Formatori IeFP, dove le verrà richiesto, come primo accesso, il consenso a pubblicare il suo CV ed i suoi dati sulla pagina dedicata.\n\nGrazie della collaborazione.\n\n') ?>
<?= "\n"; ?>
<?= Html::beginTag('a', ['href' => $appLink . 'admin/security/insert-auth-data?token=' . $profile->user->password_reset_token]) ?>
<?= AmosAdmin::t('amosadmin', 'Link di accesso alla piattaforma'); ?>
<?= Html::endTag('a'); ?>
<?= "\n"; ?>
<?= AmosAdmin::t('amosadmin', 'Se il link non funziona copia e incolla il seguente in una finestra del tuo browser di navigazione') ?>
<?= $appLink . 'admin/security/insert-auth-data?token=' . $profile->user->password_reset_token ?>
<?= "\n"; ?>
<?= "\n"; ?>

<?= AmosAdmin::t('amosadmin', '#footer_credential_mail', [
    'appName' => $appName,
    'nome' => $profile->nome,
    'cognome' => $profile->cognome,
    'email' => $profile->user->email,
]) ?>
<?= "\n"; ?>
<?= "\n"; ?>
<?= $appLink; ?>
