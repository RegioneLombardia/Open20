<?php

use open20\design\components\bootstrapitalia\ActiveForm;
use open20\design\components\bootstrapitalia\Input;
use preference\userprofile\models\PreferenceUserTargetAttribute;
use preference\userprofile\utility\TargetAttributeUtility;
use preference\userprofile\utility\TargetTagUtility;
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
        <div class="mb-5">
            <h1 class="h2 mb-5">Aggiornamento email</h1>
            <?php
            $form = ActiveForm::begin([
                'enableClientScript' => true,
                'options' => [
                    'id' => 'email-form',
                    'class' => 'needs-validation form-rounded',
                    'enctype' => 'multipart/form-data',
                ],
            ]);
            ?>

            <?= $form->field($model, 'email', [
                'errorOptions' => [
                    'class' => $model->error_class_email,
                ],
            ])->textInput([
                    'id' => 'preference-email-id',
                    'readonly' => ($model->getScenario() == \preference\userprofile\models\UserUpdateEmail::SCENARIO_OTP),
            ]); ?>

            <?php
            if ($model->getScenario() == \preference\userprofile\models\UserUpdateEmail::SCENARIO_OTP) :
                ?>
                <div class="form-group">
                    <?php
                    if (!empty($dateExpire)) :
                    ?>
                        <p>Codice valido fino alle ore: <?= $dateExpire ?></p>
                    <?php
                    endif;
                    ?>
                    <div class="form-row">
                    <div class="col-auto">
                    <?= $form->field($model, 'auth_code')->textInput(); ?>
                    </div>
                    <div class="col-auto">
                    <button type="submit" class="btn btn-tertiary" id="send-new-auth-code">Invia nuovo codice</button>
                        <div id="status-send"></div>
                    </div>
                    
                    </div>
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
            echo Html::a(
                'Annulla',
                Url::to(['user-profile']),
                ['class' => 'btn btn-outline-primary']
            );
            ?>

            <?php
            echo Html::submitButton(
                'Aggiorna',
                ['class' => 'btn btn-primary']
            );
            ?>

            <?php
            //VarDumper::dump($model->getScenario(),3,true);
            ActiveForm::end();


            //            $this->registerJs(
            //                <<<JS
            //
            //
            //JS
            //,
            //                View::POS_READY
            //            );
            ?>
        </div>
    </div>
</div>