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

//id del campo: se specificato nelle option uso quello, altrimenti sarà nel formato 'campo_db-id'
$provinciaAttribute = $provinciaConfig['attribute'];
$id = isset($provinciaConfig['options']['id']) ? $provinciaConfig['options']['id'] : $widget->generateFieldId($model, $provinciaAttribute);

$label = isset($provinciaConfig['options']['label']) ? $provinciaConfig['options']['label'] : null;
$divId = isset($provinciaConfig['options']['divId']) ? $provinciaConfig['options']['divId'] : null;
$style = isset($nazioneConfig['options']['style']) ? $nazioneConfig['options']['style'] : null;
?>

<div class="<?= isset($provinciaConfig['class']) ? $provinciaConfig['class'] : 'col-md-' . $colMdRow; ?>" id="<?= $divId ?>" style="<?= $style ?>">
    <?= $form->field($model, $provinciaAttribute)->widget(\kartik\select2\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\open20\amos\comuni\models\IstatProvince::find()->orderBy('nome')->asArray()->all(), 'id', 'nome'),
        'options' => array_merge(
            [
                'placeholder' => AmosComuni::t('amoscomuni', '#select_province_placeholder'),
                'id' => $id,
            ], !empty($provinciaConfig['options']) ? $provinciaConfig['options'] : []
        ),
        'pluginOptions' => array_merge(
            [
                'allowClear' => true
            ], !empty($provinciaConfig['pluginOptions']) ? $provinciaConfig['pluginOptions'] : []
        ),
    ])->label($label);
    ?>
</div>
