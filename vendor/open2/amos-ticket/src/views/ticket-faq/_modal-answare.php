<?php
/**
 * @var \open2\amos\ticket\models\TicketFaq $model
 */
\yii\bootstrap\Modal::begin([
    'id' => "answare-" . $model->id,
    'header' => "Risposta",
    'toggleButton' => [
        'label' => "Leggi tutto",
        'class' => "btn btn-link btn-link-table"
    ],
]);
echo $model->risposta;
\yii\bootstrap\Modal::end();
