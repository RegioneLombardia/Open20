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
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>

<div class="<?= $capClass ?>">
<?= $form->field($model, $capAttribute)->widget(DepDrop::class, [
        'type' => DepDrop::TYPE_SELECT2,
        'data' => ArrayHelper::merge(
            ['' => AmosComuni::t('amoscomuni', '#dd_placeholder_cap')],
            $dataProviderCAP
        ),
        'options' => ArrayHelper::merge(
            ['id' => $cap_id],
            !empty($capConfig['options']) ? $capConfig['options'] : []
        ),
        'select2Options' => ArrayHelper::merge([
                'pluginOptions' => ['allowClear' => true]
            ],
            !empty($capConfig['select2Options']) ? $capConfig['select2Options'] : []
        ),
        'pluginOptions' => ArrayHelper::merge([
                'depends' => [$comune_id],
                'initDepends' => $comune_id,
                'placeholder' => AmosComuni::t('amoscomuni', '#dd_placeholder_cap'),
                'url' => Url::to(['/comuni/default/caps-by-comune']),
                'params' => [$cap_id],
            ],
            !empty($capConfig['pluginOptions']) ? $capConfig['pluginOptions'] : []
        ),
        'pluginEvents' => [
            //il change viene chiamato al cambio del padre: comune
            "depdrop:afterChange" => "function(event, id, value, count) { 
                clearValueIfParentEmpty($(this));
            }",
        ],
    ]);

?>
</div>
