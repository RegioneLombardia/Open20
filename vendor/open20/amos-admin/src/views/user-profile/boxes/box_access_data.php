<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile\boxes
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use yii\helpers\FileHelper;
use yii\web\View;

/** @var yii\web\View $this */
/** @var open20\amos\core\forms\ActiveForm $form */
/** @var open20\amos\admin\models\UserProfile $model */
/** @var open20\amos\core\user\User $user */
/** @var bool $spediscicredenzialienable */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

$showCredenzialLoginSection =
    UserProfileUtility::checkShowLoginFieldsAndCredenzialSections();

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

$loggedUserIsAdmin = Yii::$app->user->can('ADMIN');
$loggedUserIsAmministratoreUtenti = Yii::$app->user->can('AMMINISTRATORE_UTENTI');

?>


<section class="account-admin-section">
    <div class="row">


        <div class="col-xs-12">
            <?php
            if ($loggedUserIsAdmin) {
            ?>
                <div class="alert-box-admin">
                    <div class="icon-alert">
                        <span class="mdi mdi-alert-outline mdi-36px text-warning"></span>
                    </div>

                    <div class="alert-text">
                        <h4 class="text-warning"><?= AmosAdmin::t('amosadmin', 'Informazioni visibili solo a utente ADMIN'); ?></h4>
                        <div class="row m-t-20">
                            <div class="col-md-6">
                                <?php if ($adminModule->confManager->isVisibleFieldInForm('username')) : ?>
                                    <?= Html::beginTag('p', ['class' => 'field-user-username']) ?>
                                    <?= Html::tag('span', $user->getAttributeLabel('username')) ?>
                                    <?= Html::tag('strong', $user->username ? $user->username : AmosAdmin::t('amosadmin', 'Non ancora definito')) ?>
                                    <?= Html::endTag('p') ?>

                                <?php endif; ?>
                                <?= Html::beginTag('p') ?>
                                <?= Html::tag('span', AmosAdmin::t('amosadmin', '#creation_date')) . ':' ?>
                                <?= Html::tag('strong', ($model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : AmosAdmin::t('amosadmin', '#not_available'))) ?>
                                <?= Html::endTag('p') ?>
                                <?= Html::beginTag('p') ?>
                                <?= Html::tag('span', AmosAdmin::t('amosadmin', '#last_update_date') . ':') ?>
                                <?= Html::tag('strong', ($model->updated_at ? Yii::$app->formatter->asDatetime($model->updated_at) : AmosAdmin::t('amosadmin', '#not_available'))) ?>
                                <?= Html::endTag('p') ?>
                            </div>
                            <div class="col-md-6">
                                <?php if ($adminModule->confManager->isVisibleFieldInForm('attivo')) : ?>
                                    <?= Html::beginTag('p') ?>
                                    <?= Html::tag('span', AmosAdmin::t('amosadmin', 'Stato')) ?>
                                    <?= Html::tag('strong', ($model->attivo ? AmosAdmin::t('amosadmin', 'Active') : AmosAdmin::t('amosadmin', 'Deactivated'))) ?>
                                    <?= Html::endTag('p') ?>
                                <?php endif; ?>
                                <?php if ($adminModule->confManager->isVisibleFieldInForm('ultimo_accesso')) : ?>
                                    <?= Html::beginTag('p') ?>
                                    <?= Html::tag('span', AmosAdmin::t('amosadmin', 'Ultimo accesso:')) ?>
                                    <?= Html::tag('strong', ($model->ultimo_accesso ? Yii::$app->formatter->asDatetime($model->ultimo_accesso) : AmosAdmin::t('amosadmin', 'Nessun accesso'))) ?>
                                    <?= Html::endTag('p') ?>
                                <?php endif; ?>

                                <?php if ($adminModule->confManager->isVisibleFieldInForm('ultimo_logout')) : ?>
                                    <?= Html::beginTag('p') ?>
                                    <?= Html::tag('span', AmosAdmin::t('amosadmin', 'Ultimo logout:')) ?>
                                    <?= Html::tag('strong', ($model->ultimo_logout ? Yii::$app->formatter->asDatetime($model->ultimo_logout) : AmosAdmin::t('amosadmin', 'Nessun logout'))) ?>
                                    <?= Html::endTag('p') ?>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <h3>
        <?= AmosAdmin::t('amosadmin', 'Account'); ?>
    </h3>
    <?php if ($showCredenzialLoginSection): ?>
    <div class="m-t-30">
        <?php if (!$model->isNewRecord && isset($user['email']) && strlen(trim($user['email']))) {
            /** @var \open20\amos\core\user\User $identity */
            $identity = Yii::$app->user->identity;
            if (Yii::$app->user->can('CHANGE_USER_PASSWORD') && ($user['id'] == $identity->id) && !empty($user['password_hash'])) {?>
                    <div class="row m-b-30 m-t-30">
                        <div class="col-md-8">
                            <p><strong><small><?= AmosAdmin::t('amosadmin', 'MODIFICA PASSWORD'); ?></small></strong></p>

                        </div>
                        <div class="col-md-offset-1 col-md-3">

                            <?= Html::a(AmosIcons::show('unlock') . AmosAdmin::t('amosadmin', 'Cambia password'), ['/' . AmosAdmin::getModuleName() . '/user-profile/cambia-password', 'id' => $model->id], [
                                'class' => 'btn  btn-action-primary btn-cambia-password btn-block'
                            ]); ?>

                        </div>
                    </div>
            <?php } ?>

        <?php }
        if (!$model->isNewRecord && isset($user['email']) && strlen(trim($user['email']))) {
            if (Yii::$app->user->can("GESTIONE_UTENTI")) {
                if ($spediscicredenzialienable) { ?>
                    <div class="row">
                        <div class="col-md-8">
                            <div><strong><small class="text-uppercase"><?= AmosAdmin::t('amosadmin', 'SPEDISCI CREDENZIALI'); ?></small></strong></div>
                            <p><small><?= AmosAdmin::t('amosadmin', 'Ricevi username e password alla mail associata al tuo profilo'); ?></small></p>
                        </div>
                        <div class="col-md-offset-1 col-md-3">
                            <?= $this->renderPhpFile(
                                FileHelper::localize(
                                    $this->context->getViewPath() . DIRECTORY_SEPARATOR . 'help' . DIRECTORY_SEPARATOR . 'send-recovery-password.php'
                                ),
                                ['model' => $model]
                            ); ?>
                        </div>
<!--                        <div class="col-md-8">
                           <div><strong><small class="text-uppercase"><?= AmosAdmin::t('amosadmin', 'SPEDISCI CREDENZIALI'); ?></small></strong></div>
                           <p><small><?= AmosAdmin::tHtml('amosadmin', 'Per spedire le credenziali occorre impostare il Ruolo nella sezione AMMINISTRAZIONE') ?></small></p>
                        </div>
                        <div class="col-md-offset-1 col-md-3">
                            <div class="btn btn-action-primary btn-block disabled" data-toggle="tooltip" data-placement="left" title="<?= AmosAdmin::t('amosadmin', 'Per spedire le credenziali occorre impostare il Ruolo nella sezione AMMINISTRAZIONE'); ?>"><?= AmosAdmin::t('amosadmin', 'Spedisci credenziali'); ?></div>
                        </div>-->
                    </div>
                <?php }
            }
        } ?>
    </div>
    <?php endif; ?>
    <?php if ($model->isActive() && Yii::$app->user->can('DeactivateAccount', ['model' => $model])) : ?>
        <div class="row m-b-30 m-t-30">
            <div class="col-md-8">
                <div><strong><small><?= AmosAdmin::t('amosadmin', 'DISATTIVA UTENTE'); ?></small></strong></div>
                <p><small><?= AmosAdmin::t('amosadmin', 'Il tuo profilo sarà disattivato e non più visibile agli altri partecipanti, non ti sarà permessa alcuna interazione con la piattaforma e saranno disattivate tutte le notifiche email. Ti sarà possibile richiedere la riattivazione in qualunque momento contattando il servizio di assistenza.'); ?></small></p>
            </div>
            <div class="col-md-offset-1 col-md-3">


                <?= Html::a(AmosAdmin::t('amosadmin', 'Deactivate user'), ['/' . AmosAdmin::getModuleName() . '/user-profile/deactivate-account', 'id' => $model->id], [
                    'id' => 'deactivate-account-btn',
                    'class' => 'btn btn-danger btn-block',
                    'title' => AmosAdmin::t('amosadmin', 'Deactivate user'),
                    //                'data-confirm' => AmosAdmin::t('amosadmin', 'Do you really want to deactivate your user') . '?'
                ]) ?>
                <!-- < ?= Html::beginTag('p') ?>
            < ?= Html::tag('span', AmosAdmin::t('amosadmin', 'Questa operazione disattiva temporaneamente l\'utente')) ?>
        < ?= Html::endTag('p') ?> -->

            </div>
        </div>
    <?php endif; ?>

    <div class="row m-b-30">
        <div class="col-md-8">
            <div><strong><small><?= AmosAdmin::t('amosadmin', 'CANCELLA UTENTE'); ?></small></strong></div>
            <p><small><?= AmosAdmin::t('amosadmin', 'L\'operazione è irreversibile e comporta la cancellazione completa del profilo utente e di tutte le impostazioni. Per un nuovo accesso sarà necessario registrarsi nuovamente.'); ?></small></p>
        </div>
        <div class="col-md-offset-1 col-md-3">



            <?php if ($model->isDeactivated() && ($loggedUserIsAdmin || $loggedUserIsAmministratoreUtenti)) : ?>
                <?= Html::a(AmosAdmin::t('amosadmin', 'Reactivate this user'), ['/' . AmosAdmin::getModuleName() . '/user-profile/reactivate-account', 'id' => $model->id], [
                    'id' => 'reactivate-account-btn',
                    'class' => 'btn btn-navigation-primary btn-block m-t-10',
                    'title' => AmosAdmin::t('amosadmin', 'Reactivate this user'),
                    //                'data-confirm' => AmosAdmin::t('amosadmin', 'Do you really want to reactivate this user') . '?'
                ]) ?>

            <?php endif; ?>


            <?php if ($loggedUserIsAdmin) { // Only ADMIN can directly drop an account
                $urlDropAccount = ['/' . AmosAdmin::getModuleName() . '/user-profile/drop-account', 'id' => $model->id];
            } else {
                $urlDropAccount = ['/' . AmosAdmin::getModuleName() . '/user-profile/drop-account-by-email', 'id' => $model->id];
            } ?>
            <?php if ($loggedUserIsAdmin) : ?>
                <?= Html::a(
                    AmosAdmin::t('amosadmin', '#delete_user'),
                    ['/' . AmosAdmin::getModuleName() . '/user-profile/drop-account', 'id' => $model->id],
                    [
                        'id' => 'drop-account-btn',
                        'class' => 'btn btn-danger btn-block m-t-10',
                        'title' => AmosAdmin::t('amosadmin', '#delete_user_data'),
                        'data-url-confirm' => AmosAdmin::t('amosadmin', '#delete_user_data')
                    ]
                ) ?>
                <p class="text-center"><small><?= AmosAdmin::t('amosadmin', 'Questa operazione è irreversibile'); ?></small></p>

            <?php elseif (Yii::$app->user->can('UpdateOwnUserProfile', ['model' => $model])) : ?>
                <?= Html::a(
                    AmosAdmin::t('amosadmin', '#delete_user'),
                    ['/' . AmosAdmin::getModuleName() . '/user-profile/drop-account-by-email', 'id' => $model->id],
                    [
                        'id' => 'drop-account-btn',
                        'class' => 'btn btn-danger btn-block m-t-10',
                        'title' => AmosAdmin::t('amosadmin', '#delete_user'),
                        //'data-confirm' => AmosAdmin::t('amosadmin', '#delete_user_data')
                    ]
                ) ?>

            <?php endif; ?>
        </div>
    </div>

</section>