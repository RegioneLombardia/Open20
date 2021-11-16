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

$this->title = AmosAdmin::t('amosadmin', 'Reimposta password {appName}', ['appName' => $appName]);
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
                                <?= AmosAdmin::tHtml('amosadmin', 'Gentile {nome} {cognome},', [
                                    'nome' => Html::encode($profile->nome),
                                    'cognome' => Html::encode($profile->cognome)
                                ]); ?>
                            </span>
                            <br />
                            <?= AmosAdmin::tHtml('amosadmin', "#forgot_password_request_message") ?>
                        </p>

                        <p style="margin-bottom: 20px;">
                            <?php
                            $seconds = Yii::$app->params['user.passwordResetTokenExpire'];

                            if($seconds >= 86400) {
                                $passwordResetTokenExpire = floor($seconds / (3600 * 24));
                                if($passwordResetTokenExpire == 1){
                                    $textDay = 'giorno';
                                } else {
                                    $textDay = 'giorni';
                                }
                            }else {
                                if(floor($seconds / 60)>=60){
                                    $textDay = chr(8);
                                    $passwordResetTokenExpire = sprintf("%d ore",floor($seconds / (60*60)));
                                } else {
                                    $textDay = 'minuti';
                                    $passwordResetTokenExpire = floor($seconds / 60);
                                }

                            }

                            $passwordResetTokenExpire = $passwordResetTokenExpire . ' ' . $textDay;
                            ?>
                            <?= AmosAdmin::tHtml('amosadmin', '#forgot_password_expire_message', [
                                'passwordResetTokenExpire' =>  $passwordResetTokenExpire,
                                'supportEmail' => Yii::$app->params['supportEmail']
                            ]); ?>
                            <?php
                            $link = $appLink . AmosAdmin::getModuleName() . '/security/insert-auth-data?token=' . $profile->user->password_reset_token;
                            if(!empty($community)) {
                                $link .= '&community_id='.$community->id;
                            }
                            if (isset($urlPrevious) && !empty($urlPrevious)) {
                                $link .= '&url_previous=' . $urlPrevious;
                            }

                            ?>
                            <?= Html::beginTag('a', ['href' => $link]) ?>
                            <?= AmosAdmin::tHtml('amosadmin', '#forgot_password_link'); ?>
                            <?= Html::endTag('a'); ?>
                        </p>

                        <p style="margin-bottom: 20px;">
                            <?= AmosAdmin::tHtml('amosadmin', '#forgot_password_broken_link_message') ?>
                            <?= AmosAdmin::tHtml('amosadmin', $link) ?>
                        </p>

                        <p style="margin-bottom: 20px;">
                            <?= AmosAdmin::tHtml('amosadmin', '#forgot_password_closing_message') ?>
                        </p>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>
