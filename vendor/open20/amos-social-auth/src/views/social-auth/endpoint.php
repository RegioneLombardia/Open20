<?php

/**
 * @var $this \yii\web\View
 * @var $backTo string
 */

$js = <<<JS
window.location.href = "$backTo";
JS;

$this->registerJs($js);