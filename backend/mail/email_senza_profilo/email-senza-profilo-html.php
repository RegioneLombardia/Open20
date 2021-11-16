<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user common\models\User */

$appLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);
//$appLinkPrivacy = Yii::$app->urlManager->createAbsoluteUrl(['/admin/user-profile/privacy']);
$appName = Yii::$app->name;

//$this->title = Yii::t('mail/corsi/standard', 'Notifica domade iscrizione ai corsi - {appName}', ['appName' => $appName]);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Roboto');
/** @var $profile \open20\amos\admin\models\UserProfile */
?>

<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <div id="header" style="
                    background-color: #ffffff;
                    padding: 0;
                    float: left;
                    width: 100%;">
                <?= Html::img(Yii::$app->urlManager->createAbsoluteUrl(['/img/header_mail.jpg']),
                    ['style' => 'float: left; width: 600px;'
                    ]) ?>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="corpo" style="background-color:#fff;padding:50px;">                

                <div class="sezione" style="overflow:hidden;color:#000000;">
                    <div class="testo">
                        <?php
                        foreach ($corpo as $paragrafo):
                        ?>
                        <p>
                            <?= $paragrafo ?>
                        </p>
                        <?php
                        endforeach;
                        ?>
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
                     Yii::t('mail/domandeiscrizione/standard', '{appName} - {appLink}', [
                         'appName' => $appName,
                         'appLink' => Html::a(Html::encode($appLink), $appLink, [
                             'style' => "text-decoration:underline; color:#00742e;"
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
                Yii::t('mail/domandeiscrizione/standard', 'questo messaggio ti è stato inviato automaticamente dalla piattaforma {appName}.', [
					'appName' => $appName,
                ])
                ?>
            </div>
        </td>
    </tr>
</table>