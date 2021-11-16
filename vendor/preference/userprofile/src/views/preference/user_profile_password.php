<?php
use open20\design\components\bootstrapitalia\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\View;

?>
<div class="row variable-gutters my-5">
    <!-- START SIDEBAR -->
    <div class="col-lg-3 affix-parent d-none d-lg-block">
        <div class="sidebar-wrapper it-line-right-side sidebar-preference h-100 pt-5">
        </div>
    </div>
    <!-- START MAIN -->
    <div class="col-lg-9 mt-4">
<h1 class="h2">Aggiornamento password</h1>
<p>Compila i seguenti campi per aggiornare la password</p>
<?php
$form = ActiveForm::begin([
        'enableClientScript' => true,
        'options' => [
            'id' => 'passowrd-form',
            'class' => 'needs-validation form-rounded',
            'enctype' => 'multipart/form-data',
        ],
    ]);
?>

<?php
if (!empty($userProfile->user->password_hash)): 
    ?>
    <div class="form-group mt-5">
    <?= $form->field($model, 'password_current')->passwordInput() ?>
    </div>
    <?php
endif;
?>
<div class="form-group">
<?= $form->field($model, 'password')->passwordInput() ?>
</div><div class="form-group">
<?= $form->field($model, 'password_repeat')->passwordInput() ?>
</div>

<?php
echo Html::a('Annulla', Url::to(['user-profile']),
    ['class' => 'btn btn-outline-primary']
);
?>

<?php
echo Html::submitButton('Aggiorna',
    ['class' => 'btn btn-primary']
);
?>

<?php

ActiveForm::end(); 


$this->registerJs(<<<JS


JS
  ,  View::POS_READY);
?>
    </div>
</div>