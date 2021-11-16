<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\parts
 * @category   CategoryName
 */

use open20\amos\core\forms\CreateNewButtonWidget;

/** @var \yii\web\View $this */

$createNewWidgetConfig = [];
if (isset($this->params['createNewBtnParams']) && !is_null($this->params['createNewBtnParams']) && is_array($this->params['createNewBtnParams'])) {
    $createNewWidgetConfig = $this->params['createNewBtnParams'];
}

?>
<div class="new-button">
    <?php if (isset($this->params['forceCreateNewButtonWidget']) || Yii::$app->controller->can('CREATE')) : ?>
        <?php $createNewWidgetConfig['btnClasses'] = 'btn btn-xs btn-primary'; ?>
        <?= CreateNewButtonWidget::widget($createNewWidgetConfig) ?>
    <?php endif; ?>
</div>