<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile\boxes
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\core\helpers\Html;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

$js = "
$('#deactivate-account-btn').on('click', function(event) {
    event.preventDefault();
    var ok = confirm('" . AmosAdmin::t('amosadmin', 'Do you really want to deactivate your user') . '?' . "');
    if (ok) {
        window.location.href = $(this).attr('href');
    }
});
$('#reactivate-account-btn').on('click', function(event) {
    event.preventDefault();
    var ok = confirm('" . AmosAdmin::t('amosadmin', 'Do you really want to reactivate this user') . '?' . "');
    if (ok) {
        window.location.href = $(this).attr('href');
    }
});
";
$this->registerJs($js, View::POS_READY);
?>

<section class="account-admin-section col-xs-12 nop">
    <h2>
        <!--        < ?= AmosIcons::show('account') ?>-->
        <?= AmosAdmin::t('amosadmin', 'Account'); ?>
    </h2>
    <div class="col-xs-4 col-sm-6 nop">
        <?php if ($adminModule->confManager->isVisibleField('ultimo_accesso', ConfigurationManager::VIEW_TYPE_FORM)): ?>
            <?= Html::beginTag('p') ?>
            <?= Html::tag('span', AmosAdmin::t('amosadmin', 'Ultimo accesso:')) ?>
            <?= Html::tag('strong', ($model->ultimo_accesso ? Yii::$app->formatter->asDatetime($model->ultimo_accesso) : AmosAdmin::t('amosadmin', 'Nessun accesso'))) ?>
            <?= Html::endTag('p') ?>
        <?php endif; ?>

        <?php if ($adminModule->confManager->isVisibleField('ultimo_logout', ConfigurationManager::VIEW_TYPE_FORM)): ?>
            <?= Html::beginTag('p') ?>
            <?= Html::tag('span', AmosAdmin::t('amosadmin', 'Ultimo logout:')) ?>
            <?= Html::tag('strong', ($model->ultimo_logout ? Yii::$app->formatter->asDatetime($model->ultimo_logout) : AmosAdmin::t('amosadmin', 'Nessun logout'))) ?>
            <?= Html::endTag('p') ?>
        <?php endif; ?>

        <?php if ($adminModule->confManager->isVisibleField('attivo', ConfigurationManager::VIEW_TYPE_FORM)): ?>
            <?= Html::beginTag('p') ?>
            <?= Html::tag('span', AmosAdmin::t('amosadmin', 'Stato')) ?>
            <?= Html::tag('strong', ($model->attivo ? AmosAdmin::t('amosadmin', 'Active') : AmosAdmin::t('amosadmin', 'Deactivated'))) ?>
            <?= Html::endTag('p') ?>
        <?php endif; ?>
    </div>

    <?php if ($model->isActive() && Yii::$app->user->can('DeactivateAccount', ['model' => $model])): ?>
        <div class="col-xs-8 col-sm-6 text-right">
            <?= Html::a(AmosAdmin::t('amosadmin', 'Deactivate user'), ['/'.AmosAdmin::getModuleName().'/user-profile/deactivate-account', 'id' => $model->id], [
                'id' => 'deactivate-account-btn',
                'class' => 'btn btn-danger',
                'title' => AmosAdmin::t('amosadmin', 'Deactivate user'),
//                'data-confirm' => AmosAdmin::t('amosadmin', 'Do you really want to deactivate your user') . '?'
            ]) ?>
        </div>
    <?php endif; ?>
    <?php if ($model->isDeactivated() && (Yii::$app->user->can('ADMIN') || Yii::$app->user->can('AMMINISTRATORE_UTENTI'))): ?>
        <div class="col-md-6 col-sm-6">
            <?= Html::a(AmosAdmin::t('amosadmin', 'Reactivate this user'), ['/'.AmosAdmin::getModuleName().'/user-profile/reactivate-account', 'id' => $model->id], [
                'id' => 'reactivate-account-btn',
                'class' => 'btn btn-navigation-primary',
                'title' => AmosAdmin::t('amosadmin', 'Reactivate this user'),
//                'data-confirm' => AmosAdmin::t('amosadmin', 'Do you really want to reactivate this user') . '?'
            ]) ?>
        </div>
    <?php endif; ?>
    <div class="clearfix"></div>
</section>
<section class="m-t-30">
    <h2>
        <!--        < ?= AmosIcons::show('account') ?>-->
        <?= AmosAdmin::t('amosadmin', 'Delete Account'); ?>
    </h2>

    <div class="col-xs-4 col-sm-6 nop">
            <?= Html::beginTag('p') ?>
            <?= Html::tag('span', AmosAdmin::t('amosadmin', 'This change is Irreversible')) ?>
            <?= Html::endTag('p') ?>
    </div>

    <div class="col-xs-8 col-sm-6 text-right">
        <?php if(\Yii::$app->user->can('ADMIN')){
            $urlDropAccount = ['/'.AmosAdmin::getModuleName().'/user-profile/drop-account', 'id' => $model->id];
        } else {
            $urlDropAccount = ['/'.AmosAdmin::getModuleName().'/user-profile/drop-account-by-email', 'id' => $model->id];
        }?>
        <?= Html::a(AmosAdmin::t('amosadmin', 'Delete user'), $urlDropAccount, [
            'id' => 'drop-account-btn',
            'class' => 'btn btn-danger',
            'title' => AmosAdmin::t('amosadmin', 'Delete user'),
            'data-confirm' => AmosAdmin::t('amosadmin', 'Do you really want to delete your user and all data? This is Irreversible!')
        ]) ?>
    </div>
    <div class="clearfix"></div>
</section>
