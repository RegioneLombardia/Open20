<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\security
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\user\User;

/**
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var \open20\amos\admin\models\RegisterForm $model
 */

ModuleAdminAsset::register(Yii::$app->view);

$assetBundle = \open20\amos\admin\assets\ModuleUserProfileAsset::register($this);

$this->title = AmosAdmin::t('amosadmin', "Associazione IDPC");

/** @var User $user */
$user = $model->user;

?>

<style>
    .page-header {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .page-header h1 {
        font-weight: bold !important;
        color: black !important;
        font-size: 40px !important;
        letter-spacing: -1px;
    }

    .page-content {
        padding: 50px 100px;
    }

    @media (max-width: 768px) {
        .page-content {
            padding: 25px 50px;
        }
    }

    @media (max-width: 575px) {
        .page-content {
            padding: 15px 25px;
        }
    }
</style>
<div class="reconciliation-page">
    <div class="col-md-12 nop">
        <p class="subtitle"><?= AmosAdmin::t('amosadmin', '#reconciliation_page_help', ['platform' => \Yii::$app->name]) ?></p>
    </div>
    <div class="two-column-section col-md-12 nop ">
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="internal-container">
                    <h2 class="single-section-title"><?= AmosAdmin::t('amosadmin', "#reconciliation_your_data") ?></h2>
                    <div class="info-container flexbox flexbox-column">
                        <p class="info-label"><?= $model->getAttributeLabel('nome') . ': ' ?></p>
                        <div class="info-content flexbox"><p class="data"><?= $model->nome ?></p> <span class="am am-check"></span></div>
                    </div>
                    <div class="info-container flexbox flexbox-column">
                        <p class="info-label"><?= $model->getAttributeLabel('cognome') . ': ' ?></p>
                        <div class="info-content flexbox"><p class="data"><?= $model->cognome ?></p> <span class="am am-check"></span></div>
                    </div>
                    <div class="info-container flexbox flexbox-column">
                        <p class="info-label"><?= $user->getAttributeLabel('email') . ': ' ?></p>
                        <div class="info-content flexbox"><p class="data" data-toggle="tooltip" data-placement="top" data-html="true" title="<span class='inner-mine-tooltip'><?= $user->email ?></span>"><?= $user->email ?></p> <span class="am am-check"></span></div>
                    </div>
                    <div class="arrow-right">
                        <!--<span class="am am-chevron-right am-5"></span>-->
                        <?= Html::img($assetBundle->baseUrl . '/img/divisoria-riconciliazione.PNG') ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="internal-container">
                    <h2 class="single-section-title"><?= AmosAdmin::t('amosadmin', "#reconciliation_enter_with_idpc") ?></h2>
                    <?php
                    $spidBtnTitle = AmosAdmin::t("amosadmin", '#reconciliation_access_digital_identity');
                    ?>
                    <?= Html::a($spidBtnTitle, [
                        '/admin/user-profile/connect-spid',
                        'id' => $model->id,
                        'redirectUrl' => \Yii::$app->params['platform']['backendUrl'] . '/admin/security/reconciliation?done=true'
                    ], [
                        'class' => 'btn btn-spid',
                        'title' => $spidBtnTitle
                    ]) ?>
                    <?= Html::img($assetBundle->baseUrl . '/img/img-riconciliazione.png', ['alt' => AmosAdmin::t('amosadmin', "#reconciliation_enter_with_idpc"), 'class' => 'idpc-reconciliation-img']) ?>
                </div>
            </div>
        </div>
        <?php if (!UserProfileUtility::mandatoryReconciliationPage()) { ?>
            <div class="row">
                <div class="col-md-12 col-xs-12 skip-button-container">
                    <?= Html::a(AmosAdmin::t('amosadmin', '#reconciliation_skip_for_now'), '/', ['class' => 'btn btn-navigation-secondary']) ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
