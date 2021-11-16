<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$appLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appLinkPrivacy = Yii::$app->urlManager->createAbsoluteUrl(['/admin/user-profile/privacy']);
$appName = Yii::$app->name;
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', 'Nuovo iscritto nella piattaforma {appName}', [
    'appName' => $appName
        ]
)
?>
<?= "\n"; ?>
<?= Yii::t('amosplatform', 'Un nuovo utente si &eacute; iscritto alla piattaforma {appName}!', ['appName' => $appName]); ?>
<?= "\n"; ?>       
<?= "Ecco i dati riepilogativi del nuovo utente: "; ?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', '<b>Nome:</b>: {nome}', [
    'nome' => Html::encode($profile->nome)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', '<b>Cognome:</b> {cognome}', [
    'cognome' => Html::encode($profile->cognome)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', '<b>Cofice Fiscale:</b> {cf}', [
    'cf' => Html::encode($profile->codice_fiscale)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', '<b>Data di nascita:</b> {nascita_data}', [
    'nascita_data' => Html::encode($profile->nascita_data)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', '<b>Sesso:</b> {sesso}', [
    'sesso' => Html::encode($profile->sesso)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', '<b>Telefono:</b> {telefono}', [
    'telefono' => Html::encode($profile->telefono)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', '<b>Email:</b> {email}', [
    'email' => Html::encode($user->email)]);
?>
<?= "\n"; ?>
<?=
Yii::t('amosplatform', '<b>Nazione di nascita:</b> {nazionedinascita}', [
    'nazionedinascita' => Html::encode($profile->nascitaNazioni->nome)]);
?>

<?= "\n"; ?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', 'Questo messaggio è stato inviato automaticamente dalla piattaforma {appName}.', [
    'appName' => $appName,
])
?>
<?= "\n"; ?>
<?= "\n"; ?>
<?= $appLink; ?>


