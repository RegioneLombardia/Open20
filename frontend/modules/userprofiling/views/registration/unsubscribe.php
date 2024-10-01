<?php

//use open20\design\components\bootstrapitalia\ActiveForm;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;

/**
 * @var $model \UserRegistrationForm
 */

?>

<style>
    .page-header{
        border-bottom:0;
    }
</style>

<h1 class="m-b-10"><?= AmosAdmin::t('amosadmin', 'Benvenuto in ') . \Yii::$app->name ?> </h1>
<p class="m-b-30"><?= $sottotitolo?> </p>

<?php

$form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]);
?>

<div class="registration-form">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput([
                'value' => $model->name ?? ($idmData['nome'] ?? ''),
                'readonly' => true,
                'placeholder' => \Yii::t('application', 'Nome')
            ]) ?>
            <?= $form->field($model, 'surname')->textInput([
                'value' => $model->surname ?? ($idmData['cognome'] ?? ''),
                'readonly' => true,
                'placeholder' => \Yii::t('application', 'Cognome')
            ]) ?>
            <?= $form->field($model, 'email')->textInput([
                'id' => 'email-id',
                'readonly' => true,
                'placeholder' => \Yii::t('application', 'Email')
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <p>Sei sicuro di volerti disiscrivere da Open 2.0?</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= Html::a(\Yii::t('amosadmin', 'Cancel'), ['/'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a(\Yii::t('amosadmin', 'Conferma'), ['/registration/unsubscribe', 'token' => 100, 'confirm' => 1], ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>



