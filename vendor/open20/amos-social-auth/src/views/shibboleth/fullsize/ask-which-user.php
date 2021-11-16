<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\socialauth\views\shibboleth
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\socialauth\Module;
use open20\amos\socialauth\utility\SocialAuthUtility;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var array $userDatas
 * @var UserProfile[] $usersByCF
 */

$title = Module::t('amossocialauth', 'spid_signup_welcome', ['nome' => $userDatas['nome'], 'cognome' => $userDatas['cognome']]);
$this->title = $title

?>

<div class="loginContainerFullsize">
    <div class="login-block social-auth-spid col-xs-12 nop">
        <div class="login-body">
            <h2 class="title-login"><?= $title ?></h2>
            <h3 class="title-login"><?= Module::t('amossocialauth', '#spid_login_multi_cf_subtitle', ['cf' => $userDatas['codiceFiscale']]) ?></h3>
            <?php
            $form = ActiveForm::begin([
                'action' => Yii::$app->controller->action->id,
                'method' => 'post',
                'options' => [
                    'class' => 'default-form'
                ]
            ]);
            ?>
            <?= Select::widget([
                'name' => 'user_by_fiscal_code',
                'data' => SocialAuthUtility::makeUsersByCFReadyForSelect($usersByCF),
                'options' => [
                    'multiple' => false,
                    'placeholder' => Module::t('amoscore', 'Select/Choose') . '...',
                ],
                'class' => 'form-control',
                'id' => 'user_by_fiscal_code_id',
            ]); ?>
            <div class="row">
                <div class="col-xs-12 action">
                    <?= Html::submitButton(Module::t('amossocialauth', 'spid_signup_already_registered_btn'), ['class' => 'btn btn-administration-primary']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
