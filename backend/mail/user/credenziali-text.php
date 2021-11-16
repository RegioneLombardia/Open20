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
$sessoBenvenuto = 'Benvenuto';
if ($profile->sesso == 'Femmina') {
    $sessoBenvenuto = 'Benvenuta';
}
?>
<?= Yii::t('amosplatform', '{nome} {cognome}', [
    'nome' => Html::encode($profile->nome),
    'cognome' => Html::encode($profile->cognome)]);
?>
<?= "\n"; ?>
<?= "\n"; ?>
<?= Html::beginTag('a', ['href' => $appLink . 'site/inserisci-dati-autenticazione?token=' . $profile->user->password_reset_token]) ?>
                            <?= Yii::t('amosplatform', 'Accedi alla piattaforma {appName}',
							 [
								'appName' => $appName
							]
							
							); ?>
                            <?= Html::endTag('a'); ?>
<?= "\n"; ?>
<?= Yii::t('amosplatform', 'Se il link non funziona copia e incolla il seguente in una finestra del tuo browser di navigazione') ?>
                            <?= $appLink . 'site/inserisci-dati-autenticazione?token=' . $profile->user->password_reset_token ?>
		<?= "\n"; ?>
		<?= "\n"; ?>	
		
<?=
                Yii::t('amosplatform', '{nome} {cognome}, questo messaggio ti è stato inviato automaticamente dalla piattaforma {appName}, a cui sei registrato con l\'indirizzo email {email}.', [
					'appName' => $appName,
                    'nome' => $profile->nome,
                    'cognome' => $profile->cognome,
                    'email' => $profile->user->email,
                ])
                ?>
<?= "\n"; ?>
<?= "\n"; ?>
<?= $appLink; ?>


