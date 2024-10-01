<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket-faq
 * @category   CategoryName
 */

use open2\amos\ticket\AmosTicket;

/**
 * @var yii\web\View $this
 * @var open2\amos\ticket\models\TicketFaq $model
 */

$this->title = AmosTicket::t('amosticket', 'Crea faq');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Ticket Faq'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

$this->params['breadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Assistenza'), 'url' => '/ticket'];
$this->params['breadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Faq'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="ticket-faq-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
