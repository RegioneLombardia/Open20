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
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
?>

<div class="<?= $comuneClass ?>">
    <?= $form->field($model, $comuneAttribute)->widget(Select::class, [
        'options' => ArrayHelper::merge(
            [
                'id' => $comune_id,
                'placeholder' => AmosComuni::t('amoscomuni', '#dd_placeholder_city'),
            ], !empty($comuneConfig['options']) ? $comuneConfig['options'] : []
        ),
        'pluginOptions' => ArrayHelper::merge(
            [
                'allowClear' => true
            ], !empty($comuneConfig['pluginOptions']) ? $comuneConfig['pluginOptions'] : []
        ),
        'data' => $dataProviderComuni
    ]); ?>
</div>
