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
<?= Yii::t('amosplatform', 'Carissimo iscritto al portale {appName}', ['appName' => $appName]); ?>
<?= "\n"; ?>
<?= "per finalizzare l'iscrizione alle selezioni dei corsi"; ?>
<?php

if (!empty($corsi)) {
    foreach ($corsi as $corso) {
        $Corso = backend\modules\corsi\models\Corsi::findOne($corso);
        if ($Corso) {
            ?>
            <?= "\n\t- $Corso->titolo - $Corso->nome"; ?>
        <?php

        }
    }
}
?>
<?= "\n"; ?>
<?= "le inviamo in allegato i moduli da compilare, firmare e spedire."; ?>
<?= "\n"; ?>
<?= "Alleghiamo anche l'avviso che spiega nel dettaglio il processo di selezione, che le consigliamo di leggere bene."; ?>
<?= "\n"; ?>
<?= "Siamo sempre a disposizione per chiarimenti,"; ?>
<?= "\n"; ?>
<?= "Cordiali Saluti."; ?>
<?= "\n"; ?>


<?= "\n"; ?>
<?= "Congratulazioni!"; ?>
<?= "\n"; ?>
<?= Yii::t('amosplatform', 'Ora sei iscritto al portale {appName}', ['appName' => $appName]); ?>
<?= "\n"; ?>
<?= "Ecco i tuoi dati riepilogativi: "; ?>
<?=

Yii::t('amosplatform', 'Nuovo iscritto nella piattaforma {appName}', [
    'appName' => $appName
        ]
)
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', 'Nome: {nome}', [
    'nome' => Html::encode($profile->nome)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', 'Cognome: {cognome}', [
    'cognome' => Html::encode($profile->cognome)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', 'Cofice Fiscale: {cf}', [
    'cf' => Html::encode($profile->codice_fiscale)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', 'Data di nascita: {nascita_data}', [
    'nascita_data' => Html::encode($profile->nascita_data)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', 'Sesso: {sesso}', [
    'sesso' => Html::encode($profile->sesso)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', 'Email: {email}', [
    'email' => Html::encode($user->email)]);
?>
<?= "\n"; ?>
<?=

Yii::t('amosplatform', 'Nazione di nascita: {nazionedinascita}', [
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


