<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\organizzazioni\widgets\UserNetworkWidget;

/**
 * @var yii\web\View $this
 * @var int $userId
 * @var bool $isUpdate
 */

?>

<?= UserNetworkWidget::widget([
    'userId' => $userId,
    'isUpdate' => $isUpdate
]); ?>
