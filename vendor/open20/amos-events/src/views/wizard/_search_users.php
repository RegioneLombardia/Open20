<?php

use kartik\select2\Select2;
use open20\amos\events\models\search\UserEventSearch;
use open20\amos\events\AmosEvents;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
?>
<?php for ($i = 1; $i <= $count; $i++) { ?>
    <div id="row-search-<?= $i ?>" class="row border-bottom py-4">
        <div class="col-md-3">
            <?= $form->field($modelSearch, "includeExclude[$i]")->widget(Select2::className(), [
                'data' => [
                    UserEventSearch::FILTER_INCLUDE => AmosEvents::t('amosevents', 'Invite only'),
                    UserEventSearch::FILTER_EXCLUDE => AmosEvents::t('amosevents', "Don't invite")
                ],
                'options' => [
                    //                'placeholder' => AmosEvents::t('amosevents', "Select...")

                ]
            ])->label(AmosEvents::t('amosevents', 'Regola')) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($modelSearch, "field[$i]")->widget(Select2::className(), [
                'data' => \open20\amos\events\models\search\UserEventSearch::getListOfAttributes(),
                'options' => [
                    'id' => 'field-' . $i,
                    'placeholder' => AmosEvents::t('amosevents', "Select...")
                ]
            ])->label(AmosEvents::t('amosevents', 'Field')) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($modelSearch, "value[$i]")->widget(\kartik\depdrop\DepDrop::className(), [
                'data' => !empty($modelSearch->field[$i]) ? UserEventSearch::getAttributesValues($modelSearch->field[$i]) : null,
                'options' => [
                    'id' => 'value-' . $i,
                ],
                'pluginOptions' => [
                    'depends' => ['field-' . $i],
                    'placeholder' => AmosEvents::t('amosevents', "Select..."),
                    'url' => \yii\helpers\Url::to(['/events/wizard/get-values'])
                ]
            ])->label(AmosEvents::t('amosevents', 'Value')) ?>
        </div>

        <div class="col-md-1 d-flex align-items-center mt-md-2 mb-2">
            <?= \yii\helpers\Html::a("<strong class='rounded-icon rounded-danger am am-close text-white'></strong><span class='d-flex d-md-none text-danger'>Elimina regola</span>", '', [
                'class' => 'btn btn-icon btn-xs',
                'id' => 'btn-remove-rule',
                'data' => $i
            ]) ?>
            
        </div>
    </div>
    <?php $form->field($modelSearch, 'event_id')->hiddenInput()->label(false);?>
<?php } ?>