<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\parts
 * @category   CategoryName
 */

 use \open20\amos\core\forms\ChangeViewButtonWidget;

/** @var \yii\web\View $this */

?>
<?php if (isset($this->params['additionalButtons'])) : ?>
    <?= ChangeViewButtonWidget::widget($this->params['additionalButtons']); ?>
<?php endif; ?>