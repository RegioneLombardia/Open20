<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\core\helpers\Html;
use open20\cms\dashboard\Module;
use open20\amos\core\forms\TextEditorWidget;
use open20\cms\dashboard\utilities\Utility;
use yii\helpers\ArrayHelper;
use open20\amos\core\views\AmosGridView;
use open20\amos\core\icons\AmosIcons;

$model->processed = 1;
$model->processed_by_user = \Yii::$app->user->id;
?>
<div class="dashboards-page-create">
    <div class="dashboards-page-form">
        <?php
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
        ?>
        <h3><?= $model->title ?></h3>
        <h4><?= Module::txt('Si è scelto di respingere la richiesta. Inserire qui di seguito la motivazione del respingimento che verrà inviato al richiedente. Finché non si preme invia la richiesta rimane pendente.') ?></h4>
        <div class="row">

            <div class="col-lg-12">
                <?=
                $form->field($model, 'message')->textArea(['rows' => 5])
                ?>
                <?= $form->field($model, 'processed', ['options' => ['style' => 'display:none;']])->hiddenInput()->label(false); ?>        
                <?= $form->field($model, 'processed_by_user', ['options' => ['style' => 'display:none;']])->hiddenInput()->label(false); ?>        

            </div>

        </div>

        <div class="clearfix"></div>
        <div class="col-xs-12 note_asterisk nop">
            <p><?= Module::txt('I campi <span class="red">*</span> sono obbligatori.') ?></p>
        </div>

        <?=
        CloseSaveButtonWidget::widget([
            'model' => $model,
            'buttonNewSaveLabel' => Module::txt('Invia'),
            'closeButtonLabel' => Module::txt('Chiudi'),
            'urlClose' => ['/' . \open20\cms\dashboard\Module::getModuleName() . '/d1/pagine'],
        ]);
        ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>