<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket
 * @category   CategoryName
 */

use open2\amos\ticket\AmosTicket;

/**
 * @var yii\web\View $this
 * @var open2\amos\ticket\models\Ticket $model
 */

$this->title = AmosTicket::t('amosticket', 'Nuovo ticket');
$this->params['breadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Ticket'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="ticket-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
