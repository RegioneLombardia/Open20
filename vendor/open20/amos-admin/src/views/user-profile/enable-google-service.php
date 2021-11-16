<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

$js = <<<JS
window.close();
JS;

$this->title = \open20\amos\admin\AmosAdmin::t('amosadmin', 'Enable Google Service');

//$this->registerJs($js, \yii\web\View::POS_LOAD);

?>

<div class="col-xs-12 nop p-t-30 p-b-30">
    <?= isset($message) ? $message : '' ?>
</div>


