<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views 
 */

use backend\modules\userimporter\models\UserImportTask;
use backend\modules\userimporter\Module;
use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var UserImportTask $model
 * @var ActiveForm2 $form
 */
$this->title = Module::t('amosuserimporter', "Importa Utenti");

$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
$module = \Yii::$app->getModule('userimporter');
$importOptin = false;
if($module){
    $importOptin = $module->importOptin;
}

?>
<div class="user-import-task-form">

    <?php
    $form        = ActiveForm::begin([
        'options' => ['class' => 'needs-validation']
    ]);
    ?>
    <?php
    $form->errorSummary($model, ['class' => 'alert-danger alert fade in']);
    ?>

    <div class="row variable-gutters my-5">
        <div class="col-md-3 mr-auto">
            <p class="text-muted">
                <?=
                    Module::t(
                        'amosuserimporter',
                        "#import_message"
                    )
                ?>
            </p>
        </div>
        <div class="col-md-8">
            <div>
                <?= $form->field($model, 'name')->label(Module::t('amosevents', "Titolo dell'importazione"))?>
            </div>
            <div>
                <?php if($importOptin){
                    $textSample = Module::t(
                        'amosuserimporter',
                        "Link al file di esempio. Solamente il campo Email è obbligatorio"
                    );
               } else {
                    $textSample = Module::t(
                        'amosuserimporter',
                        "#link_sample"
                    );
                }?>
                <?=
                    $form->field($model, 'file_input')->widget(
                        AttachmentsInput::classname(),
                        [
                            'options' => [ // Options of the Kartik's FileInput widget
                                'multiple' => false, // If you want to allow multiple upload, default to false
                            ],
                            'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                                'maxFileCount' => 1, // Client max files
                                'showRemove' => false,
                                'overwriteInitial' => false,
                            ]
                        ]
                    )->label(Module::t('amosuserimporter', '#attachments_field'))->hint(Module::t(
                        'amosuserimporter',
                        '#attachments_field_hint'
                    ))
                ?>
            </div>
        </div>
        <div>
            <?= Module::t(
                        'amosuserimporter',
                        "#abuse"
                    )
        ?>
            <?= $form->field($model, 'accept')->checkbox() ?>
        </div>
        <?php
                $module = \Yii::$app->getModule(Module::getModuleName());
                if(!empty($module->url_example_file)){
            ?>
            <div class="col-md-8">

                <?= Html::a($textSample, $module->url_example_file) ?>

            </div>
        <?php
                }
            ?>
    </div>
    <div class="buttons d-flex">
        <?php
        echo Html::submitButton(
            Module::t('amosuserimporter', 'Importa utenti'),
            ['class' => 'btn btn-primary ml-auto']
        )
        ?>

    </div>
    <?php ActiveForm::end(); ?>
</div>