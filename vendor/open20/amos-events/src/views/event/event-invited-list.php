<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\events\widgets\InvitedToEventWidget;

/**
 * @var yii\web\View $this
 * @var \open20\amos\events\models\Event $model
 */
?>

<?= InvitedToEventWidget::widget(['model' => $model]); ?>
