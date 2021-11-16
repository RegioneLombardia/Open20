<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\parts
 * @category   CategoryName
 */

use open20\amos\core\views\ChangeViewBs4;

/** @var \yii\web\View $this */

?>
<?php if ($this->params['enableChangeView']) { ?>
    <?= ChangeViewBs4::widget([
        'dropdown' => Yii::$app->controller->getCurrentView(),
        'views' => Yii::$app->controller->getAvailableViews(),
    ]); ?>
<?php } ?>
