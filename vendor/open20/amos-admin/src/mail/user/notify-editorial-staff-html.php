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
                        </p>

                        <p style="margin-bottom: 20px;">
                            <?= AmosAdmin::t('amosadmin', '#text_notification_from-editorial_staff')?>
                        </p>

                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>
