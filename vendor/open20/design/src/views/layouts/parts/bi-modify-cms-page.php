<?php

use open20\design\Module;
use open20\design\utility\CmsPermissionsUtility;

$canUpdate = CmsPermissionsUtility::canUpdate();
?>

<?php
/** utenza abilitata ad sso */
if (\Yii::$app->user->can('ADMIN') || \Yii::$app->user->can('CMS_PAGE_EDITOR') || $canUpdate) {
?>

    <a href="/admin/login/login-cms-admin?redirect=<?= $currentPageUrl ?>" title="<?= Module::t('amosdesign', 'Modifica pagina') ?>" class="complementary-3-bg btn-modify-cms-page d-none d-md-flex align-items-center justify-content-center" data-toggle="popover" data-content="<?= Module::t('amosdesign', 'Clicca per modificare la pagina attuale') ?>" data-trigger="hover" role="button">
        <!-- <span class=""><?= Module::t('amosdesign', 'Modifica pagina') ?></span> -->
        <svg class="icon icon-white icon-sm">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#file-cog"></use>
        </svg>
    </a>

<?php
}
?>