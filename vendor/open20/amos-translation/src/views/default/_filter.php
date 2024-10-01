<?php

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\translation\AmosTranslation;
?>
<div class="search">

    <?php   
    $form = ActiveForm::begin([
                'action' => ['contents'],
                'method' => 'get',               
                'options' => [
                    'class' => 'default-form',                   
                ]
    ]);
    ?>    
    <div class="col-md-4 m-t-30">        
        <p>
            <strong>
                <?= AmosTranslation::tHtml('amostranslation', 'Default language: ') ?>
                <?= (isset($this->context->module->defaultLanguage) ? $this->context->module->defaultLanguage : \Yii::$app->language) ?>
            </strong>
        </p>
    </div>
    <div class="col-md-4">
        <p>
            <strong>         
                <?=
                $model->getAttributeLabel('plugin') .
                $form->field($model, 'plugin')->dropDownList(\yii\helpers\ArrayHelper::map($model->getAllPlugins()->orderBy('plugin')->all(), 'plugin', 'plugin'), ['prompt' => AmosTranslation::t('amostranslation', 'Select ...')])->label(false);
                ?>             
            </strong>
        </p>
    </div>
    <div class="col-md-4">
        <p>
            <strong>           
                <?=
                $model->getAttributeLabel('language_id') .
                $form->field($model, 'language_id')->dropDownList(\yii\helpers\ArrayHelper::map($model->getAllActiveLanguages()->orderBy('name')->all(), 'language_id', 'name'), ['prompt' => AmosTranslation::t('amostranslation', 'Select ...')])->label(false);
                ?>             
            </strong>
        </p>
    </div>


    <div class="col-xs-12">
        <div class="pull-right">            
            <?= Html::submitButton(AmosTranslation::tHtml('amostranslation', 'Filter'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <!--a><p class="text-center">Ricerca avanzata<br>
            < ?=AmosIcons::show('caret-down-circle');?>
        </p></a-->
    <?php
    ActiveForm::end();    
    ?>
</div>