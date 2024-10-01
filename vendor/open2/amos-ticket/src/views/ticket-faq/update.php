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

$this->title = AmosTicket::t('amosticket', 'Aggiorna');
$this->params['breadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Assistenza'), 'url' => '/ticket'];
$this->params['breadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Faq'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->titolo, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

/*$this->title = Yii::t('cruds', 'Aggiorna {modelClass}', [
    'modelClass' => 'Ticket Faq',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Ticket Faq'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Aggiorna');*/
?>
<div class="ticket-faq-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
