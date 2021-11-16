<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @licence GPLv3
 * @licence https://opensource.org/proscriptions/gpl-3.0.html GNU General Public Proscription version 3
 *
 * @package amos-admin
 * @category CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\amos\admin\assets\ModuleAdminAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

ModuleAdminAsset::register(Yii::$app->view);

/**
 * @var $socialAuthModule \open20\amos\socialauth\Module
 */
$socialAuthModule = Yii::$app->getModule('socialauth');

//change social url

$urlSocial = ($type == 'login') ? '/socialauth/social-auth/sign-in?provider=' : '/socialauth/social-auth/sign-up?provider=';

if(empty($communityId)) {
    $communityId = \Yii::$app->request->get('community');
}
$paramCommunity = '';
if($communityId){
    $paramCommunity = '&community='.$communityId;
}


?>
<?= Html::tag('h2', ($type == 'login') ? AmosAdmin::t('amosadmin', '#social_title_login') : AmosAdmin::t('amosadmin', '#social_title_register'), ['class' => 'title-login']) ?>
<?= Html::tag('h3', ($type == 'login') ? AmosAdmin::t('amosadmin', '#social_subtitle_login') : AmosAdmin::t('amosadmin', '#social_subtitle_register'), ['class' => 'subtitle-login']) ?>
<div class="social-buttons col-xs-12 nop">
    <?php
    foreach ($socialAuthModule->providers as $name => $config) :
        ?>
        <div class="col-xs-12 nop">
            <a
                    class="btn btn-<?= strtolower($name); ?> social-link"
                    title="<?= ($type == 'login') ? AmosAdmin::t('amosadmin', '#login_with_social') : AmosAdmin::t('amosadmin', '#register_with_social') ?> <?= $name; ?>"
                    target="_self"
                    href="<?= Yii::$app->urlManager->createAbsoluteUrl($urlSocial . strtolower($name).$paramCommunity); ?>"
            >
                <span class="am am-<?= strtolower($name); ?>"></span>
                <span class="text"><?= $name; ?></span>
            </a>
        </div>
    <?php endforeach; ?>
</div>
