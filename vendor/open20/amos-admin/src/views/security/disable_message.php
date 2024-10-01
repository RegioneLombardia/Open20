<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\security
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\core\forms\ActiveForm;

$this->title = AmosAdmin::t('amosadmin', '#disable_collaborations_notifications_title');
$this->params['breadcrumbs'][] = $this->title;

ModuleAdminAsset::register(Yii::$app->view);
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer loginContainer">
    <div class="body col-xs-12 nop">
        <?php if (isset($title)) { ?>
            <h2><?= AmosAdmin::t('amosadmin', '#disable_notification_title_error') ?></h2>
        <?php } ?>
        <h4 class="subtitle-login"><?= $message ?></h4>
        <?php if (isset($infoMessage)) { ?>
            <?= $infoMessage ?>
        <?php } ?>
    </div>


</div>
