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
use open20\amos\core\forms\ActiveForm;
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
use yii\helpers\VarDumper;
use yii\redactor\widgets\Redactor;

/**
 * @var yii\web\View $this
 * @var backend\modules\operators\models\Operator $model
 * @var yii\widgets\ActiveForm $form
 */
$js2 = <<<JS
     function enableDisableStructure(){
    var selected=$(this).children("option:selected").val()
    if(selected.includes("STRUCTURE")===true){
            $('#pc-structure').attr('disabled', false); 

    }else{
         $('#pc-structure').attr('disabled', true); 
         $('#pc-structure').val('');
         $('#pc-structure').trigger('change');
    }
    }

  $(document).ready(function(){
        $('#role-id').trigger('select2:select');
});
JS;
$this->registerJs($js2);

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

    $myModel = \backend\modules\operators\models\Operator::find()->andWhere(['user_id' => Yii::$app->user->id])->one();

    $emailReadOnly = false;
    $structureReadOnly = false;
    if (!empty($structure)) {
        $model->pc_structure_id = $structure;
        $structureReadOnly = true;
    }
    if (!$model->isNewRecord) {

        // echo Html::a(Module::t('operators', 'Elimina operatore'), ['/admin/user-profile/drop-account', 'id' => $model->id], [
        //     'id' => 'drop-account-btn ',
        //     'class' => 'btn btn-danger pull-right',
        //     'title' => Module::t('operators', 'Elimina operatore'),
        //     'data-confirm' => Module::t('operators', 'Vuoi veramente eliminare l\'utente e tutti i tuoi suoi dati? Questa operazione è irreversibile!')
        // ]);
        if ($model->isActive()) {
            echo Html::a(Module::t('operators', 'Disattiva operatore'), ['/admin/user-profile/deactivate-account', 'id' => $model->id], [
                'id' => 'deactivate-account-btn',
                'class' => 'btn btn-secondary btn-xs',
                'title' => Module::t('operators', 'Disattiva operatore'),
                //                'data-confirm' => AmosAdmin::t('amosadmin', 'Do you really want to deactivate your user') . '?'
            ]);
        } else {
            echo Html::a(Module::t('operators', 'Riattiva operatore'), ['/admin/user-profile/reactivate-account', 'id' => $model->id], [
                'id' => 'deactivate-account-btn',
                'class' => 'btn btn-secondary btn-xs',
                'title' => Module::t('operators', 'Disattiva operatore'),
                //                'data-confirm' => AmosAdmin::t('amosadmin', 'Do you really want to deactivate your user') . '?'
            ]);
        }


        if (Yii::$app->user->can('ADMIN')) {
            echo '<div class="row mt-1 float-right">';
            echo Html::a(Module::t('operators', 'Impersona operatore'), ['/admin/security/impersonate', 'user_id' => $model->user->id], [
                'class' => 'btn btn-secondary btn-xs',
                'title' => Module::t('operators', 'Impersona operatore'),
            ]);
            echo '</div>';
        }


        $model->email = $model->user->email;
        $emailReadOnly = true;
        if (!empty($model->getPCRole())) {
            $model->role = $model->getPCRole()->name;
        }
    }

    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); 
    ?>

    <div class="row mt-5">

        <div class="col-md-12">
            <!-- nome string -->


            <h2 class="subtitle-form h5 font-weight-bold text-uppercase primary-color mb-5">Anagrafica</h2>
            <div class="row variable-gutters">
                <div class="col-md-6 form-group">
                    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
                </div><!-- cognome string -->
                <div class="col-md-6 form-group">
                    <?= $form->field($model, 'cognome')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6 form-group">
                    <?= $form->field($model, 'codice_fiscale')->textInput(['maxlength' => true]) ?>
                </div>
                <?php
                if ((Yii::$app->user->can('ADMIN'))
                    || (Yii::$app->user->can('PC_ADMIN'))
                    || (Yii::$app->user->can('PC_STRUCTURE_ADMIN'))
                    || (Yii::$app->user->id != $model->user_id)):
                    ?>
                    <div class="col-md-6 form-group">
                        <?= $form->field($model, 'role')->widget(Select2::classname(), [
                            'options' => [
                                'placeholder' => '',
                                'id' => 'role-id',
                                'disabled' => false
                            ],
                            'data' => \backend\modules\operators\models\search\OperatorSearch::roleToAssignByRole(),
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                            'pluginEvents' => [
                                "select2:select" => "enableDisableStructure"
                            ]
                        ]); ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <?php
                        //\yii\helpers\VarDumper::dump($myModel->attributes);
                        if (!empty($myModel)):
                            if (empty($myModel->pc_structure_id)) :
                                ?>
                                <?= $form->field($model, 'pc_structure_id')->widget(Select2::classname(), [
                                'options' => [
                                    'placeholder' => '',
                                    'disabled' => $structureReadOnly,
                                    'id' => 'pc-structure',
                                ],
                                'data' => ArrayHelper::map(\backend\modules\operators\models\PreferenceStructure::find()->orderBy('name')->asArray()->all(), 'id', 'name')
                            ])->label('Struttura di appartenenza');
                                ?>
                            <?php
                            else :
                                echo '<div class="alert alert-info" role="alert">
                             Puoi creare utenti legati alla tua struttura: <b>' . $myModel->preferenceStructure->name . '</b></div>';
                            endif;
                        endif;
                        ?>
                    </div>
                <?php
                else:
                    ?>
                    <div class="col-md-12 form-group">
                        il tuo ruolo è: <b><?= $model->getRoleName(); ?></b>
                        <?php
                        echo $form->field($model, 'role')->hiddenInput()->label(false);
                        ?>
                    </div>
                    <?php
                    if (!empty($model->pc_structure_id)) :
                        ?>
                        <div class="col-md-12 form-group">
                            La tua struttura di appartenenza è: <b><?= $model->preferenceStructure->name ?></b>
                        </div>
                    <?php
                    endif;
                    ?>
                <?php
                endif;
                ?>
            </div>
            <!--< ?php ActiveForm::end(); ?>-->
        </div>


    </div>
<!--    <hr class="dashed my-5">-->
    <div class="row credenziali-section mb-5">
        <div class="col-md-12">
<!--            <h2 class="subtitle-form h5 font-weight-bold text-uppercase primary-color mb-5">Credenziali</h2>-->
            <div class="row variable-gutters">
                <div class="col-md-6 form-group">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly' => $emailReadOnly]) ?>
                </div>

                <?php
                $roleArray = [];
                if (!$model->isNewRecord) {
                    $roleArray = Yii::$app->authManager->getRolesByUser($model->user->id);
                }
//                \yii\helpers\VarDumper::dump($roleArray);

                if((!$model->isNewRecord) && ((isset($roleArray['PC_REGISTERD_USER']) && !empty($roleArray['PC_REGISTERD_USER']))) ):
                    echo '<div class="float-left">';
                    echo '<span class="badge badge-pill badge-primary">utente abilitato ad accedere in front-end</span>';
                    echo '</div>';
                else:
                    ?>
                    <div class="col-md-12 form-group">
                        <?= $form->field($model, 'frontend_role_flag')->checkbox(['readonly' => $emailReadOnly]) ?>
                    </div>
                    <?php
                endif;

                ?>

            </div>
            <?php
            /*
            if (!$model->isNewRecord) { ?>
                <p>Password</p>
                <?= Html::a('modifica password', ['/admin/user-profile/cambia-password', 'id' => $model->id], [
                    'class' => 'btn btn-secondary btn-xs',
                    'title' => 'Modifica la tua password',

                ]); ?>

                <?= Html::a(Module::t('operators', 'Reset password'), ['/admin/security/spedisci-credenziali', 'id' => $model->id], [
                    'class' => 'btn btn-secondary btn-xs',
                    'title' => 'Fai il reset della tua password',
                ]);
                ?>
            <?php }

            */
            ?>
            <div class="my-3">
                <?= RequiredFieldsTipWidget::widget(); ?>
            </div>


        </div>
    </div>
    <!--<div class="clearfix"></div>-->
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>

</div>