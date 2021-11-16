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

$this->title = Yii::t('amosplatform', 'Iscrizione alla piattaforma {appName}', ['appName' => $appName]);
?>

<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <div class="corpo" style="background-color:#fff;padding:50px;">               
                <div class="sezione titolo" style="overflow:hidden;color:#000000;">
                    <h2 style="padding:5px 0;	margin:0;">Nuovo iscritto</h2>
                </div>
                <div class="sezione" style="overflow:hidden;color:#000000;">
                    <div class="testo">
 <?= Yii::t('amosplatform', 'Carissimo iscritto al portale {appName},', ['appName' => $appName]); ?>
                        <br/>  
                        per finalizzare l'iscrizione alle selezioni dei corsi  
                        <ul>
                            <?php if(!empty($corsi)){
                                    foreach ($corsi as $corso){ 
                                $Corso = backend\modules\corsi\models\Corsi::findOne($corso);
                                if($Corso){
                                ?>
                            <li><a href="/public/corso?id=<?= $corso ?>"><?= $Corso->titolo . ' - ' . $Corso->nome ?></a></li>
                            <?php }}} ?>
                        </ul>
                        le inviamo in allegato i moduli da compilare, firmare e spedire
                        <br/>
                        Alleghiamo anche l'avviso che spiega nel dettaglio il processo di selezione, che le consigliamo di leggere bene.
                        <br/>
                        Siamo sempre a disposizione per chiarimenti,
                        <br/>
                        Cordiali Saluti.
                        <br/>
                        <br/>
                        
                        
                        Dati riepilogativi dell'iscrizione: 
                        <br />
                        <p><?=
                            Yii::t('amosplatform', '<b>Nome:</b>: {nome}', [
                                'nome' => Html::encode($profile->nome)]);
                            ?>
                        </p>
                        <p>
                            <?=
                            Yii::t('amosplatform', '<b>Cognome:</b> {cognome}', [
                                'cognome' => Html::encode($profile->cognome)]);
                            ?>
                        </p>
                        <p>
                            <?=
                            Yii::t('amosplatform', '<b>Cofice Fiscale:</b> {cf}', [
                                'cf' => Html::encode($profile->codice_fiscale)]);
                            ?>
						</p>
                        <p>
                            <?=
                            Yii::t('amosplatform', '<b>Data di nascita:</b> {nascita_data}', [
                                'nascita_data' => Html::encode($profile->nascita_data)]);
                            ?>
						</p>
                        <p>
                            <?=
                            Yii::t('amosplatform', '<b>Sesso:</b> {sesso}', [
                                'sesso' => Html::encode($profile->sesso)]);
                            ?>
                        </p>
                        <p>                          
                            <?=
                            Yii::t('amosplatform', '<b>Email:</b> {email}', [
                                'email' => Html::encode($user->email)]);
                            ?>                                                       
                        </p>
                        <p>   
                            <?=
                            Yii::t('amosplatform', '<b>Nazione di nascita:</b> {nazionedinascita}', [
                                'nazionedinascita' => Html::encode($profile->nascitaNazioni->nome)]);
                            ?>                                                     
                        </p>                                                
                    </div>
                </div>
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <div id="footer"
                 style="padding:20px 50px;background-color:#ffffff;">
                     <?=
                     Yii::t('amosplatform', '{appName} - {appLink}', [
                         'appName' => $appName,
                         'appLink' => Html::a(Html::encode($appLink), $appLink, [
                             'style' => "text-decoration:underline;"
                         ]),
                     ])
                     ?>


            </div>
        </td>
    </tr>

    <tr>
        <td>
            <div class="sezione bottom" style="padding:15px 50px;color:#233C32;font-size:11px;">
                <?=
                Yii::t('amosplatform', 'Questo messaggio è stato inviato automaticamente dalla piattaforma {appName}.', [
                    'appName' => $appName,
                ])
                ?>
            </div>
        </td>
    </tr>
</table>
