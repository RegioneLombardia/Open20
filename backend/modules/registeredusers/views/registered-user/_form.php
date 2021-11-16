<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/operators/views
 */

use backend\modules\operators\Module;
use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\attachments\components\CropInput;
use yii\bootstrap4\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\PasswordInput;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\redactor\widgets\Redactor;
use open20\amos\core\utilities\ViewUtility;
use open20\amos\core\models\AttributesChangeLog;
use kartik\depdrop\DepDrop;
/**
 * @var yii\web\View $this
 * @var backend\modules\operators\models\Operator $model
 * @var yii\widgets\ActiveForm $form
 */


?>



<div class="operator-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'operator_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);

    $emailReadOnly = false;
    $structureReadOnly = false;
    if (!empty($structure)) {
        $model->pc_structure_id = $structure;
        $structureReadOnly = true;
    }
    if (!$model->isNewRecord) {
        
        // echo Html::a(Module::t('operators', 'Elimina Utente'), ['/admin/user-profile/drop-account', 'id' => $model->id], [
        //     'id' => 'drop-account-btn ',
        //     'class' => 'btn btn-xs btn-danger mr-2',
        //     'title' => Module::t('operators', 'Elimina Utente'),
        //     'data-confirm' => Module::t('operators', 'Vuoi veramente eliminare l\'utente e tutti i tuoi suoi dati? Questa operazione è irreversibile!')
        // ]);
//         echo Html::a(Module::t('operators', 'Disattiva Utente'), ['/admin/user-profile/deactivate-account', 'id' => $model->id], [
//             'id' => 'deactivate-account-btn',
//             'class' => 'btn btn-secondary btn-xs',
//             'title' => Module::t('operators', 'Disattiva operatore'),
// //                'data-confirm' => AmosAdmin::t('amosadmin', 'Do you really want to deactivate your user') . '?'
//         ]);
        if ($model->isActive()) {
            echo Html::a(Module::t('operators', 'Disattiva utente'), ['/admin/user-profile/deactivate-account', 'id' => $model->id], [
                'id' => 'deactivate-account-btn',
                'class' => 'btn btn-secondary btn-xs',
                'title' => Module::t('operators', 'Disattiva utente'),
                //                'data-confirm' => AmosAdmin::t('amosadmin', 'Do you really want to deactivate your user') . '?'
            ]);
        } else {
            echo Html::a(Module::t('operators', 'Riattiva utente'), ['/admin/user-profile/reactivate-account', 'id' => $model->id], [
                'id' => 'deactivate-account-btn',
                'class' => 'btn btn-secondary btn-xs',
                'title' => Module::t('operators', 'Disattiva utente'),
                //                'data-confirm' => AmosAdmin::t('amosadmin', 'Do you really want to deactivate your user') . '?'
            ]);
        }
        $model->email = $model->user->email;
        $emailReadOnly = true;

    }

    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

	<div class="row variable-gutters mt-5">
		<div class="col-md-3">
            
            <div>
    
                <?php if (!$model->isNewRecord) { ?>
	                <p><strong>Utente iscritto il:</strong><br>
	            <?php $create=new \DateTime($model->created_at);
	            echo $create->format('d/m/Y'); ?>
                    </p>
                    <p><strong>Privacy:</strong><br>
                <?php

                    $query = AttributesChangeLog::find();
                    $query->andWhere([
                        'model_id' => $model->id,
                        'model_attribute' => 'privacy'
                    ]);
                    $query->andWhere(['like', 'model_classname', 'User']);

                    $logList = $query->orderBy(['created_at' => SORT_DESC])->one();
                    $privacy=new \DateTime($logList->created_at);
                    echo $privacy->format('d/m/Y');


                ?>


                <?php
                if (!empty($model->pc_request_delete_date)):
                ?>

                <p><strong>Utente disiscritto il:</strong><br>
	            <?php $delete=new \DateTime($model->pc_request_delete_date);
	            echo $delete->format('d/m/Y'); ?>
                    </p>
                <?php
                endif;
                ?>
                    <p><strong>Sistema di provenienza:</strong><br>
                <?php

                   
                    echo $model->preferenceOriginSystem->name;


                ?>
                <?php } ?>
                </p>
                <?php
                /*
                ?>
                <p><strong>Test notifica app</strong></p>
                <p><?php  
                    echo Html::a('Push...', ['test-app-notify', 'id' => $model->id], [
                    'id' => 'deactivate-account-btn',
                    'class' => 'btn btn-secondary btn-xs',
                    'title' => Module::t('operators', 'Notifica di test'),
                    'data-confirm' => 'Sicuro di inviare una notifica di test?'
                ]); ?></p>
                <?php
                */
                ?>
            </div>


			
        </div>
        <div class="col-md-9"><!-- nome string -->
                <h4 class="subtitle-form h5 font-weight-bold text-uppercase primary-color mb-3">Anagrafica</h4>
                <div class="mb-5">
            <?php
            foreach (\preference\userprofile\utility\UserProfileUtility::getIDMFields(Yii::$app->user->id) as $key => $value):
                ?>
                    <div class="mb-2">
                    <b><?= \preference\userprofile\utility\UserProfileUtility::getIDMLabels($key)?>: </b>
                    <?= $value ?>
                    </div>
                
            <?php
            endforeach;
            ?>
            </div>


                <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?><!-- cognome string -->
                <?= $form->field($model, 'cognome')->textInput(['maxlength' => true]) ?><!-- codice_fiscale string -->
                <?php // echo $form->field($model, 'codice_fiscale')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'nascita_data')->widget(DateControl::classname(), [
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'disabled' => false,
                    ],
                    'autoWidget' => false,
                    'saveOptions' => [
                        'type' => 'text',
                        'class' => 'sr-only',
                        'label' => '<label for="nascita_data-disp" class="sr-only">' . Module::t('campains', 'Data nascita') . '</label>'
                    ],
                    'widgetOptions' => [
                        'mask' => '99-99-9999',
                    ]
                ]) ?>
                <?=
                $form->field($model, 'sesso',
                    [
                        'template' => "{label}\n{hint}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                    ])->widget(Select::classname(),
                    [
                        'options' => ['placeholder' => Module::t('amosadmin', 'Select/Choose').'...', 'disabled' => false],
                        'data' => [
                            'None' => Module::t('amosadmin', '#undefinded'),
                            'Maschio' => Module::t('amosadmin', '#man'),
                            'Femmina' => Module::t('amosadmin', '#women')
                        ]
                    ])->label(Module::t('campains', 'Genere'));
                ?>

				<div class="row">
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'provincia_residenza_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(open20\amos\comuni\models\IstatProvince::find()->orderBy(['nome' => SORT_ASC])->asArray()->all(), 'id', 'nome'),
                                'language' => substr(Yii::$app->language, 0, 2),
                                'options' => [
                                    'id' => 'provincia_residenza_id',
                                    'multiple' => false,
                                    'placeholder' => 'Seleziona ...',
                                    'data-toggle' => 'tooltip',
                                ],
                                'class' => 'dynamicCreation' . $append,
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ])->label( \Yii::t('app','Provincia') )
                        ?>
                    </div>
					<div class="col-md-6">
                        <?=
                            $form->field($model, 'comune_residenza_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(open20\amos\comuni\models\IstatComuni::find()->orderBy(['nome' => SORT_ASC])->asArray()->all(), 'id', 'nome'),
                                'language' => substr(\Yii::$app->language, 0, 2),
                                'options' => [
                                    'id' => 'comune_residenza_id',
                                    'multiple' => false,
                                    'placeholder' => 'Seleziona ...',
                                    'data-toggle' => 'tooltip',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ])
                        ?>
					</div>
				</div>
                <!--< ?php ActiveForm::end(); ?>-->
            </div>
    </div>
    <hr class="dashed my-5">

 


    <div class="row credenziali-section">
        <div class="col-md-9 offset-md-3">
<!--        <h4 class="subtitle-form h5 font-weight-bold text-uppercase primary-color mb-5">Credenziali</h4>-->
                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly' => $emailReadOnly]) ?>

                <?php
                /*
                if (!$model->isNewRecord) { ?>
                    <p>Password</p>
                    <?= Html::a('modifica password', ['/admin/user-profile/cambia-password', 'id' => $model->id], [
                        'class' => 'btn btn-secondary btn-xs',
                        'title' => 'Modifica la tua password',
                        
                    ]);?>

                    <?= Html::a(Module::t('operators', 'Reset password'),['/admin/security/spedisci-credenziali', 'id' => $model->id], [
                            'class' => 'btn btn-secondary btn-xs',
                            'title' => 'Fai il reset della tua password',
                        ]);
                    ?>
                <?php }

                */ ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-9 offset-md-3">
            <?php
            /*
                echo Html::a(Module::t('operators', 'Disattiva operatore'), ['/admin/user-profile/deactivate-account', 'id' => $model->id], [
                    'id' => 'deactivate-account-btn',
                    'class' => 'btn btn-secondary btn-xs',
                    'title' => Module::t('operators', 'Disattiva operatore'),
                ]);
            */
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9 offset-md-3">
            <?= $form->field($model, 'privacy')->checkbox([0, 1]); ?>
        </div>
    </div>


    <hr class="dashed my-5">
    <div class="row">     
        
        <div class="col-md-9 offset-md-3">
            <?= $this->render('_targetETematiche',
                [
                    'model' => $model,
                    'form' => $form,
//                    'tag' => $tag,
//                    'profili' => $profili,
//                    'wizardAsset' => $wizardAsset,
//                    'spriteAsset' => $spriteAsset,
                ])
            ?>





                <?= RequiredFieldsTipWidget::widget(); ?>
                
                <?= CloseSaveButtonWidget::widget(['model' => $model, 'buttonClassSave' => 'btn btn-primary']); ?>
                <?php ActiveForm::end(); ?>
        </div>
    </div>
    <!--<div class="clearfix"></div>-->

	
</div>


<?php

/** PAGE SCRIPTS */
$script = <<< JS
    $('input[name="RegisteredUser[codice_fiscale]"]').keyup(function(){
        $(this).val($(this).val().toUpperCase()); 
    });


    $("#provincia_residenza_id").ready(function(){
        var provincia_residenza_id = $("#provincia_residenza_id").val();
        var url = "/registeredusers/registered-user/get-comuni-by-id-provincia" ;
        var data = "provincia_residenza_id="+provincia_residenza_id;
        var comune = $('#comune_residenza_id').val();

        ajaxCallComuni(url, data, comune);
    });

    $("#provincia_residenza_id").change(function(){
        var provincia_residenza_id = $("#provincia_residenza_id").val();
        var url = "/registeredusers/registered-user/get-comuni-by-id-provincia" ;
        var data = "provincia_residenza_id="+provincia_residenza_id;
        var comune = $('#comune_residenza_id').val();

        ajaxCallComuni(url, data, comune);

        $('#comune_residenza_id').val("").change();
    });

    function ajaxCallComuni(url, data, comune){
    
        $('#comune_residenza_id option').remove();
    
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "html",
            success: function(risposta){
                var comuni = JSON.parse(risposta);

                var select_option = '<option value="">Seleziona il comune</option>';
                comuni.forEach(element => {
                    if(element.id == comune){
                        select_option += '<option value="'+element.id+'" selected>'+element.nome+'</option>';
                    }else{
                        select_option += '<option value="'+element.id+'">'+element.nome+'</option>';
                    }
                });
                
                $('#comune_residenza_id').append(select_option);
            },
            error: function(xhr, status, error) {
                // alert(error);
                // alert(xhr.responseText);
            }
        });
    }



JS;

$this->registerJs($script);
?>



