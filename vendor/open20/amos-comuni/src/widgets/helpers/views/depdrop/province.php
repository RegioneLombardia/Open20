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

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\depdrop\DepDrop;

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

<div class="<?= $provinciaClass ?>">
    <?= $form->field($model, $provinciaAttribute)->widget(DepDrop::class, [
        'type' => DepDrop::TYPE_SELECT2,
        'data' => ArrayHelper::merge(
            ['' => AmosComuni::t('amoscomuni', '#dd_placeholder_province')],
            !empty($dataProviderProvince) ? $dataProviderProvince : []
        ),
        'options' => ArrayHelper::merge(
            [
                'id' => $provincia_id,
            ], !empty($provinciaConfig['options']) ? $provinciaConfig['options'] : []
        ),
        'pluginOptions' => ArrayHelper::merge(
            [
                'depends' => [$nazione_id],
                'placeholder' => AmosComuni::t('amoscomuni', '#dd_placeholder_province'),
                'url' => Url::to(['/comuni/default/province-by-nazione?soppresso=0']),
                'params' => [$id],
            ], !empty($provinciaConfig['pluginOptions']) ? $provinciaConfig['pluginOptions'] : []
        ),
        'select2Options' => ArrayHelper::merge(
            [
                'pluginOptions' => ['allowClear' => true]
            ], !empty($comuneConfig['select2Options']) ? $comuneConfig['select2Options'] : []
        ),
        'pluginEvents' => [
            //il change viene chiamato al cambio del padre: provincia
            "depdrop:afterChange" => "function(event, id, value, count) { 
                clearValueIfParentEmpty($(this));
             }",
        ],

    ]); ?>
 </div>
