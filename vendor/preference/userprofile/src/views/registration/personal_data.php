<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 * @var $idmData array
 * @var $model \preference\userprofile\models\StepPersonalData
 */


use open20\design\components\bootstrapitalia\ActiveForm;
use open20\amos\core\helpers\Html;
use yii\helpers\Url;
use preference\userprofile\utility\UserProfileUtility;
?>

<?php
$form = ActiveForm::begin([
    'enableClientScript' => true,
    'options' => [
        'id' => 'personal-data-form',
        'class' => 'needs-validation form-rounded',
        'enctype' => 'multipart/form-data',
    ],
]);
?>




<div class="lightgrey-bg-c1 py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h1>Dati personali</h1>
                <p class="tertiary-color">Completa i dati personali con cui desideri accedere al servizio</p>
            </div>
            <div class="col-md-3">
                <ul class="wizard-steps text-center">
                    <li class="active-step  current-step">
                        <div>1</div>
                    </li>
                    <li class="  ">
                        <div>2</div>
                    </li>
                    <li class="  ">
                        <div>3</div>
                    </li>
                    <li class=" ">
                        <div>4</div>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>



<?php
//\yii\helpers\VarDumper::dump(UserProfileUtility::getIDMFields(null), 3, true);
//\yii\helpers\VarDumper::dump($idmData, 3, true);
?>



<div class="py-5 mb-5 container border-bottom border-light border-light">
    <div class="informazioni-personali-container  mt-3 mb-5  d-flex flex-column">
        <h2 class="mb-4 text-uppercase h5"> Informazioni personali </h2>
        <div class="row variable-gutters mb-5">
            <input type="hidden" id="steppersonaldata-fiscal_code"
                   class="form-control"
                   name="StepPersonalData[fiscal_code]"
                   value="<?= isset($idmData['codiceFiscale'])? $idmData['codiceFiscale']: ''?>"
            >
            <?php
            foreach (UserProfileUtility::getIDMFields(null) as $key => $value):
                ?>
                <div class="col-md-4">
                    <b><?= UserProfileUtility::getIDMLabels($key)?>: </b>
                    <br /><?= $value ?>
                </div>
                <?php
            endforeach;
            ?>
        </div>

        <div class="row variable-gutters">
            <div class="col-md-4">
                <?php
                $params = [];
                if (empty($model->name)) {
                    $params = [
                        'value' => isset($idmData['nome'])? $idmData['nome']: '',
                    ];
                }
                ?>
                <?= $form->field($model, 'name')->textInput($params) ?>
            </div>
            <div class="col-md-4">
                <?php
                $params = [];
                if (empty($model->surname)) {
                    $params = [
                        'value' => isset($idmData['cognome'])? $idmData['cognome']: '',
                    ];
                }
                ?>
                <?= $form->field($model, 'surname')->textInput($params) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'gender')->radioList($model->getGenderChoices(),[
                    'general-list-label' => Yii::t('preferenceuser', 'Sesso')
                ]) ?>
            </div>
        </div>
        <div class="row variable-gutters">
            <div class="col-md-4">

                <?=
                    $form->field($model, 'birth_date')->inputCalendar(['placeholder' => 'dd/mm/YYYY'], 'd/m/Y')
                ?>

            </div>
            <div class="col-md-4">

                <?=
                    $form->field($model, 'residence_province')->select( $items,['placeholder' => 'Scegli una provincia', 'id' => 'residence_province-id'])
                ?>

            </div>
            <div class="col-md-4">

               <?=
                   $form->field($model, 'residence_city')->select( null,[
                       'placeholder' => 'Scegli un comune',
                       'data-action' => Url::to('test-data-ajax'),
                       'related-id' => 'residence_province-id',
                       ])
               ?>

            </div>

            <?php
                /*
                    <div class="col-md-4">
                        <?= $form->field($model, 'fiscal_code')->textInput([
                                'aria-describedby' => 'Perchè devo fornire il mio Codice Fiscale?',
                                'infoTooltip' => 'Questo dato consentirà il tuo riconoscimento come utente del sistema anche in caso di accesso tramite SPID, in questo modo non rischierai di ricevere più volte lo stesso messaggio',
                        ]); ?>
                    </div>
                */
            ?>

    </div>

    <div class="credenziali-container">
<!--        <h2 class="mb-3 text-uppercase h5">Credenziali</h2>-->
        <div class="row variable-gutters pt-3">
            <div class="col-md-4">
                <?php
                $params = [
                    'id' => 'preference-email-id',
                    'label' => Yii::t('preferenceuser', 'Indirizzo email'),
                    'readonly' => ($model->getScenario() == \preference\userprofile\models\StepPersonalData::SCENARIO_OTP)
                ];
                if (empty($model->email)) {
                    $params = [
                        'id' => 'preference-email-id',
                        'label' => Yii::t('preferenceuser', 'Indirizzo email'),
                        'readonly' => ($model->getScenario() == \preference\userprofile\models\StepPersonalData::SCENARIO_OTP),
                        'value' => isset($idmData['emailAddress'])? $idmData['emailAddress']: '',
                    ];
                }
                ?>
                <?= $form->field($model, 'email', [
                        'errorOptions' => [
                            'class' => $model->error_class_email,
                        ],
                    ])->textInput($params)
                ?>
            </div>

            <?php
            if ($model->getScenario() == \preference\userprofile\models\StepPersonalData::SCENARIO_OTP):
                ?>
                <div class="col-md-4">
                    <?= $form->field($model, 'auth_code')->textInput(['value' => '']) ?>
                </div>
                <div class="col-md-4">
                <button type="submit" class="btn btn-tertiary" id="send-new-auth-code">Invia nuovo codice</button>
                    <div id="status-send"></div>
                </div>
                <?php
                $toSendOtp = Url::to('/preferenceuser/registration/send-otp');
                // JS che pilota invia nuovo codice
                $this->registerJs(
                    <<<JS

                    $("button[id^='send-new-auth-code']").click(function(event) {  
                        $('#status-send').html('');
                        event.preventDefault();  
                        $(this).attr("disabled", true);
                        $.post("{$toSendOtp}", { 
                                email: '' + $("#preference-email-id").val(),
                            }).done(function( data ) {
                                if(data){
                                  if (data.status === 'ok') {
                                      $('#status-send').html('<span class="badge badge-success">Codice inviato correttamente</span>');
                                  } else {
                                      $('#status-send').html('<span class="badge badge-danger">Codice inviato non inviato correttamente</span>');
                                  }
                                }
                                $("button[id^='send-new-auth-code']").removeAttr("disabled");
                        });
                    });

JS
                    ,
                    \yii\web\View::POS_READY
                );



               endif;
            ?>

            <?php
            /*
            ?>
            <div class="col-md-4">
                <?= $form->field($model, 'password')->passwordInput([
                    'label' => Yii::t('preferenceuser', 'Imposta password'),
                    'helperTooltip' => 'La password deve contenere almeno: 8 caratteri, lettere maiuscole e minuscole ed almeno un numero'
                ])
                ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'password_repeat')->passwordInput([
                    'label' => Yii::t('preferenceuser', 'Ripeti password')
                ]) ?>
            </div>
            <?php
            */
            ?>



        </div>

        <?php
        /*
        ?>
        <div class=" pt-5  text-center">

        <?= Html::tag('span', Html::encode('Se sei già registrato a Lombardia Informa '), ['class' => 'text-muted username mr-sm-2']) ?>



            <?= Html::a('Accedi', Url::to('/preferenceuser/preference/settings')) ?>
        </div>
        <?php
        */
        ?>





    </div>











</div>

<div class="container">
    <div class="d-flex justify-content-center justify-content-sm-between mb-0 mb-sm-5">


    <div>
        <?php
        echo Html::a(
            Yii::t('preferenceuser', 'Annulla'),
            ['/'],
            [
                'class' => 'btn btn-outline-primary d-none d-sm-block px-5',
                'title' => Yii::t('preferenceuser', 'Torna in home page'),
            ]
        );
        ?>

    </div>
    <div class="d-flex justify-content-center">
        <?php
//        echo Html::a(
//            Yii::t('preferenceuser', 'Indietro'),
//            ['preferences'],
//            [
//                'class' => 'btn btn-outline-primary mr-2 px-5',
//                'title' => Yii::t('preferenceuser', 'Torna allo step precedente'),
//            ]
//        );
        ?>

        <?php
        echo Html::submitButton(
            'Continua',
            ['class' => 'btn btn-primary px-5', 'name' => 'submit-action', 'value' => 'forward']
        );
        ?>
    </div>
</div>

<div class="mobile-wizard-button mb-5 d-flex justify-content-center d-sm-none">
    <?php
    echo Html::a(Yii::t('preferenceuser', 'Annulla registrazione'), ['/'], [
        'class' => 'text-decoration-none text-secondary pt-4 px-5',
        'title' => Yii::t('preferenceuser', 'Torna in home page'),
    ]);
    ?>
</div>

</div>

<?php
//\yii\helpers\VarDumper::dump($model->getScenario(),3,true);
//\yii\helpers\VarDumper::dump($model->errors,3,true);

ActiveForm::end(); ?>