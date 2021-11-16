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
use open20\amos\core\icons\AmosIcons;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 * @var bool $spediscicredenzialienable
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;
$enableDlSpemplification = $adminModule->enableDlSemplification;
//pr($enableDlSpemplification);

?>
<section class="access-admin-section col-xs-12 nop">
    <h2>
        <!--        < ?= AmosIcons::show('lock') ?>-->
        <?= AmosAdmin::tHtml('amosadmin', 'Dati di Accesso') ?>
    </h2>
    <?php if ($adminModule->confManager->isVisibleField('username', ConfigurationManager::VIEW_TYPE_FORM)): ?>
        <div class="col-xs-4 col-sm-6 nop">
            <?= Html::beginTag('p', ['class' => 'field-user-username']) ?>
            <?= Html::tag('span', $user->getAttributeLabel('username')) ?>
            <?= Html::tag('strong', $user->username ? $user->username : AmosAdmin::t('amosadmin', 'Non ancora definito')) ?>
            <?= Html::endTag('p') ?>
        </div>
    <?php endif; ?>

    <?php if (!$enableDlSpemplification) { ?>
        <div id="user-password" class="col-xs-8 col-sm-6 text-right pull-right">
            <div id="form-credenziali" class="bk-form-credenziali">
                <?php // if (!$model->isNewRecord && isset($user['email']) && strlen(trim($user['email']))):
                //if($spediscicredenzialienable) {
                ?>
                <?php if (!$model->isNewRecord && isset($user['email']) && strlen(trim($user['email']))): ?>
                    <?php if (Yii::$app->getUser()->can("GESTIONE_UTENTI")): ?>
                        <?php if ($spediscicredenzialienable): ?>
                            <?= Html::a(
                                AmosIcons::show('email') . AmosAdmin::t('amosadmin', 'Spedisci credenziali'),
                                [
                                    '/' . AmosAdmin::getModuleName() . '/security/spedisci-credenziali',
                                    'id' => $model->id
                                ],
                                [
                                    'class' => 'btn btn-navigation-primary btn-spedisci-credenziali ',
                                    'title' => AmosAdmin::t('amosadmin', 'Permette l\'invio di una mail contenente un link temporale per modificare le proprie credenziali di accesso.'),
                                    'data-confirm' => AmosAdmin::t('amosadmin', 'Sei sicuro di voler inviare le credenziali? Sarà inviata una mail contenente un link per modificare le credenziali. Vuoi continuare?')
                                ]); ?>
                        <?php else: ?>
                            <div id="info-spedisci" class="btn btn-action-primary disabled" data-toggle="tooltip"
                                 data-placement="left"
                                 title="<?= AmosAdmin::t('amosadmin', 'Per spedire le credenziali occorre impostare il Ruolo nella sezione AMMINISTRAZIONE'); ?>">
                                <?= AmosAdmin::t('amosadmin', 'Spedisci credenziali'); ?>
                            </div>
                            <div class=""><?= AmosAdmin::tHtml('amosadmin', 'Per spedire le credenziali occorre impostare il Ruolo nella sezione AMMINISTRAZIONE') ?></div>
                            <div class="btn btn-action-primary disabled"><?= AmosAdmin::t('amosadmin', 'Spedisci credenziali'); ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php
                    /** @var \open20\amos\core\user\User $identity */
                    $identity = Yii::$app->user->identity
                    ?>
                    <?php if (Yii::$app->user->can('CHANGE_USER_PASSWORD') && ($user['id'] == $identity->id)): ?>
                        <?= Html::a(AmosIcons::show('unlock') . AmosAdmin::t('amosadmin', 'Cambia password'), ['/' . AmosAdmin::getModuleName() . '/user-profile/cambia-password', 'id' => $model->id], [
                            'class' => 'btn  btn-action-primary btn-cambia-password'
                        ]); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
</section>
