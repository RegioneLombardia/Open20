<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin
 * @category   CategoryName
 */

use yii\helpers\Html;
use open20\amos\videoconference\AmosVideoconference;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$appLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appName = Yii::$app->name;

$this->title = AmosVideoconference::t('amosvideoconference', 'Registrazione {appName}', ['appName' => $appName]);
$this->registerCssFile('http://fonts.googleapis.com/css?family=Roboto');
$sessoBenvenuto = AmosVideoconference::t('amosvideoconference', 'Benvenuta');
if ($userProfile['sesso'] == 'Maschio') {
    $sessoBenvenuto = AmosVideoconference::t('amosvideoconference', 'Benvenuto');
}
$this->params['profile'] = $profile;

?>


<table width=" 600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <div class="corpo"
                 style="border:1px solid #cccccc;padding:10px;margin-bottom:10px;background-color:#ffffff;margin-top:20px">

                <div class="sezione titolo" style="overflow:hidden;color:#000000;">
                    <h2 style="padding:5px 0;	margin:0;"><?=
                        AmosVideoconference::t('amosvideoconference', 'Gentile {nome} {cognome},', [
                            'nome' => Html::encode($userProfile['nome']),
                            'cognome' => Html::encode($userProfile['cognome'])]);
                        ?></h2>
                </div>
                <div class="sezione" style="overflow:hidden;color:#000000;">
                    <div class="testo">
                        <?php
                        foreach ($body as $paragraph):
                            ?>
                            <p>
                                <?= $paragraph ?>
                            </p>
                            <?php
                        endforeach;
                        ?>
                    </div>

                </div>
            </div>
        </td>
    </tr>
</table>
<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <p style="text-align:center"><?= AmosVideoconference::t('amosvideoconference', '*** Questa è una e-mail generata automaticamente, si prega di non rispondere***'); ?></p>
        </td>
    </tr>

</table>