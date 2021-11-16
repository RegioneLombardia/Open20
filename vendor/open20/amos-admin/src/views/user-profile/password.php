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

$this->title = AmosAdmin::t('amosadmin', 'Cambia password');
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Utenti'), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Elenco'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'AGGIORNA'), 'url' => ['update', 'id' => $id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-index row nom">
    <div class="tab-content">
    <h1 class="sr-only"><?= $this->title ?></h1>
        <?php
        $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off'],
        ]) ?>
        <div class="col-sm-5 col-xs-12">
            <?= $form->field($model, 'vecchiaPassword')->passwordInput(['autocomplete' => 'off']) ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-5 pull-left col-xs-12">
            <?= $form->field($model, 'nuovaPassword')->passwordInput(['autocomplete' => 'off']);
    //            $form->field($model, 'nuovaPassword')->widget(PasswordInput::classname(), [
    //            'language' => 'it',
    //            'pluginOptions' => [
    //                'showMeter' => true,
    //                'toggleMask' => false,
    //                'language' => 'it',
    //                'verdictTitles' => [
    //                    0 => AmosAdmin::t('amosadmin','Password troppo corta'),
    //                    1 => AmosAdmin::t('amosadmin','Password molto debole'),
    //                    2 => AmosAdmin::t('amosadmin','Password debole'),
    //                    3 => AmosAdmin::t('amosadmin','Password buona'),
    //                    4 => AmosAdmin::t('amosadmin','Password forte'),
    //                    5 => AmosAdmin::t('amosadmin','Password eccellente')
    //                ],
    //            ]]);

        ?>
        </div>
        <div class="col-sm-5 pull-right col-xs-12">
            <?= $form->field($model, 'ripetiPassword')->passwordInput(['autocomplete' => 'off']) ?>
        </div>

        <div class="clearfix"></div>

        <div class="bk-btnFormContainer">
    <!--        <div class="col-lg-12 col-sm-12">-->
                <?= Html::submitButton('Cambia', ['class' => 'btn btn-primary']) ?>
    <!--        </div>-->
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
