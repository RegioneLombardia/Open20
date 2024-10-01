<?php
/**
 * @var $model \open2\amos\ticket\models\TicketFaq
 */


?>

<?=
$this->render(
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-faq-item-list',
    [
        'faqId' => $model->id,
        'faqCatId' => $model->ticket_categoria_id,
        'faqQuestion' => $model->domanda,
        'faqAnswer' => $model->risposta
    ]
);
?>