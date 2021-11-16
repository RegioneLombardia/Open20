<?php

use backend\modules\campains\models\search\UserCampainSearch;
use backend\modules\campains\Module;
use kartik\select2\Select2;
use open20\amos\events\AmosEvents;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
?>
<h5><?=Module::t('campains', 'Ricerca per campi anagrafici')?></h5>

<?php for ($i = 1; $i <= $count; $i++) { ?>
    <div id="row-search-<?= $i ?>" class="row border-bottom py-4">
        <div class="col-md-3">
            <?= $form->field($modelSearch, "includeExclude[$i]")->widget(Select2::className(), [
                'data' => [
                    UserCampainSearch::FILTER_INCLUDE => Module::t('campains', 'Includi'),
                    UserCampainSearch::FILTER_EXCLUDE => Module::t('campains', 'Esculdi')
                ],
                'options' => [
                    //                'placeholder' => Module::t('campains', "Select...")

                ]
            ])->label(Module::t('campains', 'Regola')) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($modelSearch, "field[$i]")->widget(Select2::className(), [
                'data' => UserCampainSearch::getListOfAttributes(),
                'options' => [
                    'id' => 'field-' . $i,
                    'placeholder' => Module::t('campains', "Select...")
                ]
            ])->label(Module::t('campains', 'Campo')) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($modelSearch, "value[$i]")->widget(\kartik\depdrop\DepDrop::className(), [
                'data' => !empty($modelSearch->field[$i]) ? UserCampainSearch::getAttributesValues($modelSearch->field[$i]) : null,
                'options' => [
                    'id' => 'value-' . $i,
                ],
                'pluginOptions' => [
                    'depends' => ['field-' . $i],
                    'placeholder' => Module::t('campains', "Select..."),
                    'url' => \yii\helpers\Url::to(['/campains/preference-campain/get-values'])
                ]
            ])->label(Module::t('campains', 'Valore')) ?>
        </div>

        <div class="col-md-1 d-flex align-items-center mt-md-2 mb-2">
            <?= \yii\helpers\Html::a("<strong class='rounded-icon rounded-danger am am-close text-white'></strong><span class='d-flex d-md-none text-danger'>Elimina regola</span>", '', [
                'class' => 'btn btn-icon btn-xs',
                'id' => 'btn-remove-rule',
                'data' => $i
            ]) ?>
            
        </div>
    </div>
    
<?php } ?>