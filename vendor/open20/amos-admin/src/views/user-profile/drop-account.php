<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use open20\amos\admin\AmosAdmin;

//use open20\amos\core\forms\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = AmosAdmin::t('amosadmin', 'Inserisci la tua password per confermare la cancellazione.');
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Utenti'), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Elenco'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Drop Account'), 'url' => ['update', 'id' => $id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-index row nom">
    <div class="tab-content">
    <h1 class="sr-only"><?= $this->title ?></h1>
        <p><?= AmosAdmin::t('amosadmin', 'Irreversible operation, if you confirm your account and all associated data will be dropped'); ?></p>
        <?php
        $form = ActiveForm::begin([
            'id' => 'drop-form',
            'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off'],
        ]) ?>
        <div class="col-sm-5 col-xs-12">
            <?= $form->field($model, 'vecchiaPassword')->passwordInput(['autocomplete' => 'off'])->label('Password') ?>
        </div>
        <div class="clearfix"></div>

        <div class="bk-btnFormContainer">
    <!--        <div class="col-lg-12 col-sm-12">-->
                <?= Html::submitButton(AmosAdmin::t('amosadmin','Delete Account'), ['class' => 'btn btn-warning']) ?>
    <!--        </div>-->
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
