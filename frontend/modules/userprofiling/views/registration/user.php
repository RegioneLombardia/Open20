<?php

//use open20\design\components\bootstrapitalia\ActiveForm;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\notificationmanager\AmosNotify;

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
//\yii\helpers\VarDumper::dump($idmData, 10, true);

$errorText = AmosAdmin::t('amosadmin', "E' obbligatorio scegliere almeno un tag prima di salvare il profilo");
$js = <<<JS
$(document).on('ready', function(){
    var checkTagsError = false;
    var errorText = "{$errorText}";
    var selector = $('div[class*=interesttagvalues]');
    var errorDiv = selector.find('.help-block-error');
    
    $('input[type="checkbox"]').change(function() {
        if(checkTagsError){
            if($('input:checkbox:checked').length != 0){
                selector.removeClass('has-error');
                errorDiv.html('');
            }
            else {
                selector.addClass('has-error');
                errorDiv.html(errorText);
            }
        }
    });
    $(":submit").on('click', function(e){
        checkTagsError = true;
         if($('input:checkbox:checked').length == 0){
            selector.addClass('has-error');
            errorDiv.html(errorText);
        }
    });
});
JS;
$this->registerJs($js, \yii\web\View::POS_READY);


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
                'placeholder' => \Yii::t('application', 'Email')
            ]) ?>
        </div>

        <!--<div class="col-md-6 image-container">
            <img src="/img/registration.png" alt="Registrati con i tuoi dati">
        </div>-->

        <div class="col-md-12">
            <div class="flexbox align-items-end m-t-30">
                <h3 class="m-b-0"><?= AmosAdmin::t('amosadmin', 'Scegli gli argomenti di tuo interesse') . ' <span style="color: #a61919">*</span>' ?></h3>
            </div>
            <p class="m-t-20"><strong><?= AmosAdmin::t('amosadmin', 'Seleziona almeno un “Prodotto” ed una “Area di intervento” di tuo interesse per ricevere in email informazioni personalizzate') ?></strong></p>
            <?php
            echo (new \open20\amos\cwh\widgets\CardTagWidget([
                'model' => $model,
                'attribute' => 'interestTagValues',
                'form' => $form,
                'codiceRoot' => ['root_networks'],
                'showTagLabel' => true,
                'selected' => $tags
            ]))->run();
            ?>
        </div>
        <div class="col-md-12">
            <p class="m-b-20"><?= AmosAdmin::t('amosadmin', 'Indica la frequenza con cui desideri ricevere email di notifica automatica riguardo i contenuti pubblicati da ' . \Yii::$app->name) ?></p>
        </div>
        <div class="col-md-6">


            <?php
            $html = Html::beginTag('div');
            $notificationConfOpt = AmosNotify::instance()->createModel('NotificationsConfOpt');
            echo $form->field($model, 'email_frequency_id', [
                'template' => "{label}\n{hint}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
            ])->widget(Select::classname(), [
                'options' => ['placeholder' => AmosAdmin::t('amosadmin', 'Scegli un\'opzione') . '...', 'disabled' => false],
                'data' => $notificationConfOpt::emailFrequencyValueAndLabels()
            ]);
            ?>


            <?= \yii\helpers\Html::a(
                AmosAdmin::t('amosadmin', '#cookie_policy_message'),
                Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'],
                ['title' => AmosAdmin::t('amosadmin', '#cookie_policy_title'), 'target' => '_blank']
            ) ?>
            <p>Dichiaro di aver preso visione dell'<?= \yii\helpers\Html::a('informativa sul trattamento dei dati personali ai sensi dell’art. 13 del Regolamento (UE) 2016/679', Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'], ['title' => AmosAdmin::t('amosadmin', 'Show privacy informations'), 'target' => '_blank']) ?> e acconsento al trattamento dei dati per le finalità in essa indicate.</p>

            <?= $form->field($model, 'privacy')->inline(true)->radioList(
                [
                    1 => AmosAdmin::t('amosadmin', '#cookie_policy_ok'),
                    0 => AmosAdmin::t('amosadmin', '#cookie_policy_not_ok')
                ]
            ); ?>
            <?= Html::a(\Yii::t('amosadmin', 'Cancel'), ['/'], ['class' => 'btn btn-secondary']) ?>
            <?= \open20\amos\core\helpers\Html::submitButton($buttonLabel, ['class' => 'btn btn-navigation-primary']); ?>

        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>



