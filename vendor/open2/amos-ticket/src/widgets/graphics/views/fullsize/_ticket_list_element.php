<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\widgets\graphics\views\fullsize
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\widgets\graphics\WidgetGraphicAssistance;

/**
 * @var yii\web\View $this
 * @var WidgetGraphicAssistance $widget
 * @var Ticket $ticket
 * @var string $listContainerClass
 */

?>

<p><span class="<?= $listContainerClass; ?>"><?= '#'. $ticket->id; ?></span>
    <span><?= Yii::$app->getFormatter()->asDate($ticket->created_at) . ' ' . AmosTicket::t('amosticket', '#from') . ' ' . $ticket->createdUserProfile->nomeCognome; ?>
</p>
<p><?= Html::a($ticket->titolo, $ticket->getFullViewUrl()); ?></p>
