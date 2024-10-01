<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use open20\amos\admin\AmosAdmin;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('amosapp', 'Drop your account');
?>

<div class="user-profile-index container">
    <div class="tab-content">
        <h1 class="sr-only"><?= $this->title ?></h1>
        <p class="h3 m-b-30"><?= Yii::t('amosapp', "Attenzione, se confermi riceverai una email con un link per la cancellazione del tuo account e di tutti i dati a te associati. Confermi?") ?></p>
        <?php
        $form = ActiveForm::begin([
            'id' => 'drop-form',
            'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off'],
        ]) ?>
        <div class="" hidden>
            <?= Html::hiddenInput('user_id', $model->user_id) ?>
        </div>
        <div class="clearfix"></div>

        <div class="bk-btnFormContainer">

            <!--        <div class="col-lg-12 col-sm-12">-->
            <?= Html::submitButton(Yii::t('amosapp', 'Confirm delete'), ['class' => 'btn btn-danger pull-right', 'title' =>  Yii::t('amosapp', 'Drop your account')]) ?>
            <!--        </div>-->
            <?= Html::a(Yii::t('amosapp', 'Cancel'),['/preferences/change'] ,['class' => 'btn btn-secondary pull-right', 'title' =>  Yii::t('amosapp', 'Cancel')]) ?>

        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
