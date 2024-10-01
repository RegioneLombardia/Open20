<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket-categorie
 * @category   CategoryName
 */

use open2\amos\ticket\AmosTicket;

/**
 * @var yii\web\View $this
 * @var open2\amos\ticket\models\TicketCategorie $model
 */

$this->title = AmosTicket::t('amosticket', 'Crea categoria');
$this->params['breadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Assistenza'), 'url' => '/ticket'];
$this->params['breadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Categorie'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ticket-categorie-create">
    <?= $this->render('_form', [
        'model' => $model,
        'model_referenti' => $model_referenti,
        'referenti' => $referenti,
        'community' => $community,
    ]) ?>
</div>
