<?php

use amos\userauth\frontend\Module;
use open20\amos\admin\AmosAdmin;
use luya\helpers\Html;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\components\bootstrapitalia\ActiveForm;

$theModule = Module::instance();

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$socialMatch = Yii::$app->session->get('social-match');
$socialProfile = Yii::$app->session->get('social-profile');
$language_code = Yii::$app->composition['langShortCode'];

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());

$reconciliation = \Yii::$app->request->get('reconciliation');
$spidData = \Yii::$app->session->get('IDM');
$reconciliation = !empty($spidData) && !empty($reconciliation);
$enableDlSemplificationLight = $theModule->enableDlSemplificationLight;


$textButtonLogin = Module::t('Accedi');
$textButtonLogin2 = Module::t('userauth.controller.default.index.loginlabel');
if (!empty($reconciliation)) {
    $textButtonLogin = Module::t('Riconcilia');
    $textButtonLogin2 = Module::t('Riconcilia');
}
?>


<?php if (!$showLiteMode) : ?>

    <?php
    $form = ActiveForm::begin(
        [
            'options' =>
                [
                    'class' => 'userauth-login-form needs-validation form-rounded',
                    'autocomplete' => 'off'
                ]
        ]
    );
    ?>
    <?php if ($reconciliation) { ?>
        <h3><?= Module::t('Riconciliazione profilo esistente') ?></h3>
        <p><?= Module::t('Per riconciliare la tua utenza principale e recuperare tutte le tue preferenze e impostazioni, loggati un’ultima volta con il tuo username/password o attraverso uno dei tuoi profili social con cui ti sei originariamente registrato alla Piattaforma') ?></p>
    <?php } ?>
    <?php
    if ((empty($reconciliation) || !$enableDlSemplificationLight)) {

        if ($theModule->enableSPID) {
            echo $this->render(
                'parts' . DIRECTORY_SEPARATOR . 'bi-idpc',
                [
                    'currentAsset' => $currentAsset,
                    'hideSpidButtonDescription' => $hideSpidButtonDescription,
                    'hideIdpcButtonInfo' => $hideIdpcButtonInfo,

                ]
            );
        }
    }
    ?>
    <?php if ((!empty($reconciliation) || (!empty(\Yii::$app->params['showLoginStandard']) && \Yii::$app->params['showLoginStandard']) || !$enableDlSemplificationLight)) { ?>

        <?php if ($theModule && $theModule->enableUserPasswordLogin && $theModule->enableSPID) : ?>
            <hr class="mb-5">
        <?php endif; ?>

        <?php if (!empty(\Yii::$app->params['showLoginStandard']) && \Yii::$app->params['showLoginStandard']) {
            $classLoginAdmin = 'callout';
        } ?>

        <div class="<?= $classLoginAdmin ?>">
        <?php if (!empty(\Yii::$app->params['showLoginStandard']) && \Yii::$app->params['showLoginStandard']) { ?>
            <div class="callout-title">
                <svg class="icon">
                    <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-info-circle"></use>
                </svg>
                <span class="sr-only"><?= \Yii::t('app', 'Alert') ?></span> <?= \Yii::t('app', 'Sezione visibile sotto rete locale') ?>
            </div>
            <p class="mb-5"></p>
        <?php } ?>

        <?php if ($theModule && ($theModule->enableUserPasswordLogin || (!empty(\Yii::$app->params['showLoginStandard']) && \Yii::$app->params['showLoginStandard']))) : ?>


            <?=
            $form->field($model, 'username')->textInput([
                'label' => Module::t('Indirizzo email')
            ]);
            ?>
            <?= $form->field($model, 'password')->passwordInput(); ?>

            <?php if (!empty($reconciliation)) {
                \Yii::$app->session->set('connectSocialToSpid', 1);
                echo \yii\helpers\Html::hiddenInput('associateSpid', 1);
            } ?>

            <div>
                <div class="mt-2 order-md-2 mr-auto mr-md-0">
                    <p class="mb-3 text-sans-serif"><a href="<?= '/' . $language_code . $forgotPwdUrl ?>"
                                                       title="<?= Module::t('Inizia la procedura di recupero password') ?>"><?= Module::t('Hai dimenticato la password?') ?></a>
                    </p>
                </div>
                <div class="order-md-1 mr-md-auto">
                    <div class="form-check">
                        <?php if (!$reconciliation) { ?>
                            <?= $form->field($model, 'rememberme')->checkbox([
                                'value' => 1,
                                'id' => 'userloginform-rememberme-id'
                            ])->label('Ricordami') ?>
                        <?php } ?>

                    </div>
                </div>

            </div>

            <?= Html::submitButton(
                $textButtonLogin2,
                ['class' => 'btn btn-primary px-5']
            ); ?>
        <?php
        endif;
        ?>
    <?php } ?>
    </div>


    <?php $form::end(); ?>

<?php else : ?>

    <?= Html::a(
        $textButtonLogin,
        [$loginUrl],
        ['class' => 'btn btn-primary', 'title' => Module::t('Accedi alla piattaforma')]
    ); ?>

<?php endif ?>

<?php if ($theModule && $theModule->enableUserPasswordLogin && $theModule->enableSocial) : ?>
    <!-- <hr class="mb-5"> -->
<?php endif; ?>


<?php if ((!empty($reconciliation) || !$enableDlSemplificationLight)) { ?>

    <?php
    if ($theModule->enableSocial) {
        echo $this->render(
            'parts' . DIRECTORY_SEPARATOR . 'bi-social',
            [
                'currentAsset' => $currentAsset,
                'type' => 'login',
                'communityId' => null,
                'reconciliation' => $reconciliation
            ]
        );
    }
}

if($reconciliation){?>

    <div class="col-xs-12 reactivate-profile-block">
        <hr class="mb-5">

        <?= Module::t('Eri già utente della piattaforma e non ti ricordi come ti sei loggato?') ?>
        <br>
        <?= Html::a(Module::t('RIATTIVA IL PROFILO'), ['/'.AmosAdmin::getModuleName().'/security/reactivate-profile'], ['class' => '', 'title' => AmosAdmin::t('amosadmin', '#reactive_profile'), 'target' => '_self']) ?>
    </div>
<?php
}
?>