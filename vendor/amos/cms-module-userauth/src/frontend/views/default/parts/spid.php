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
use open20\amos\core\icons\AmosIcons;
use yii\helpers\Url;

ModuleAdminAsset::register(Yii::$app->view);



?>
<?= Html::tag('h2', AmosAdmin::t('amosadmin', '#fullsize_spid'), ['class' => 'title-login']) ?>
<div class="col-sm-6 col-xs-12 nop">
    <?=
    Html::a(
        AmosIcons::show('account-circle') . AmosAdmin::t('amosadmin', '#fullsize_login_spid_text'),
        Url::to('/socialauth/shibboleth/endpoint', 'https'),
        [
            'class' => 'btn btn-spid',
            'title' => AmosAdmin::t('amosadmin', '#fullsize_login_spid_text'),
            //'target' => '_blank'
        ]
    )
    ?>
</div>
<div class="col-xs-12 nop">
    <p class="spid-text"><?= AmosAdmin::t('amosadmin', '#fullsize_login_spid_text_right') ?></p>
</div>
