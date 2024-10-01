<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni\widgets\helpers\views
 * @category   CategoryName
 */

use open20\amos\comuni\AmosComuni;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var \open20\amos\comuni\widgets\helpers\AmosComuniWidget $widget
 * @var \open20\amos\core\forms\ActiveForm $form
 * @var \open20\amos\core\record\Record $model
 * @var array $nazioneConfig
 * @var array $provinciaConfig
 * @var array $comuneConfig
 * @var array $capConfig
 * @var string $colMdRow
 */
?>

<div class="<?= $nazioneClass ?>">
    <?= $form->field($model, $nazioneAttribute)->widget(Select2::class, [
        'data' => $dataProviderNazioni,
        'options' => ArrayHelper::merge([
                'placeholder' => AmosComuni::t('amoscomuni', '#dd_placeholder_nation'),
                'id' => $nazione_id,
            ],
            !empty($nazioneConfig['options']) ? $nazioneConfig['options'] : []
        ),
        'pluginOptions' => ArrayHelper::merge([
                'allowClear' => true
            ],
            !empty($nazioneConfig['pluginOptions']) ? $nazioneConfig['pluginOptions'] : []
        ),
    ]); ?>
</div>

<?php
$placeholderProvince = AmosComuni::t('amoscomuni', '#dd_placeholder_province');
$placeholderCity = AmosComuni::t('amoscomuni', '#dd_placeholder_city');

$script = <<< JS
$(document).ready(function() {
    var obj_provincia = $('#select2-{$provincia_id}');
    var obj_comune = $('#select2-{$comune_id}');
    
    var obj_lbl_provincia = $('#select2-{$provincia_id}-container');
    var obj_lbl_comune = $('#select2-{$comune_id}-container');

    $("#{$nazione_id}").on('change', function() {
        obj_lbl_provincia.html('{$placeholderProvince}');
        obj_lbl_comune.html('{$placeholderCity}');
        
        return true;
    });
        
    obj_provincia.change();
    obj_comune.change();
});
JS;
$this->registerJs($script);
?>
