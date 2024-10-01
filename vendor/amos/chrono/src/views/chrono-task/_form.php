<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views 
 */

use amos\chrono\base\ChronoTaskTimes;
use amos\chrono\models\ChronoTask;
use amos\chrono\Module;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var ChronoTask $model
 * @var ActiveForm2 $form
 */
$this->title = Module::t('amoschrono', "#new_task");

$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

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
                        'amoschrono',
                        "#chrono_message"
                    )
                ?>
            </p>
        </div>
        <div class="col-md-8">
            <div>
                <?= $form->field($model, 'name')->textInput() ?>
            </div>
            <div>
                <?= $form->field($model, 'description')->textInput() ?>
            </div>
            <?php 
                    $times = new ChronoTaskTimes();
                    $items = $times->createCronArrayList();
                ?>
            <div>
                <?= $form->field($model, 'schedule')->textInput() ?>
                <?= Html::dropDownList('choose_cron', null,
                    $items,[
                        'id' => 'choose_cron',
                        'style' => 'width: 0px;',
                        'onchange'=>'if($("#choose_cron option:selected").val() != \'\') {$("#chronotask-schedule").val($("#choose_cron option:selected").val());}'
                    ]) ?>
            </div>
            <div>
                <?= $form->field($model, 'command')->textInput() ?>
            </div>
            <div>
                <?= $form->field($model, 'active')->checkbox() ?>
            </div>

        </div>
        <div>
            
        </div>
        <div class="col-md-8">
            <?php
                if(!is_null($model->next_run)){
            ?>
           <div>
               <label><?= $model->getAttributeLabel('next_run') ?></label>
                <?= Yii::$app->formatter->asDatetime($model->next_run,'medium') ?>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
    <div class="buttons d-flex">
        <?php
        echo Html::submitButton(
            Module::t('amoschrono', '#save'),
            ['class' => 'btn btn-primary ml-auto']
        )
        ?>

    </div>
    <?php ActiveForm::end(); ?>
</div>