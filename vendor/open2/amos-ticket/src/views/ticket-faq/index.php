<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket-faq
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;
use open2\amos\ticket\AmosTicket;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open2\amos\ticket\models\search\TicketFaqSearch $model
 */

$this->title = AmosTicket::t('amosticket', 'Gestione Faq');
$this->params['breadcrumbs'][] = $this->title;
$view = $this;
?>
<div class="ticket-faq-index">
    <?php echo $this->render('_search', ['model' => $model]); ?>
    <?php
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                //  ['class' => 'yii\grid\SerialColumn'],
                //           'id',

                'ticket_categoria_id' => [
                    'attribute' => 'ticketCategoria.nomeCompleto',
                    'label' => AmosTicket::t('amosticket', 'Categoria')
                ],
                'domanda' => [
                    'attribute' => 'domanda',
                    'label' => AmosTicket::t('amosticket', 'Domanda'),
                    'value' => function ($model) {
                        return strip_tags($model->domanda);
                    },
                    'format' => 'html',
                ],
                'risposta' => [
                    'attribute' => 'risposta',
                    'label' => AmosTicket::t('amosticket', 'Risposta'),
                    'value' => function ($model) {
                        $valTruncate = 100;
                        $length = strlen(strip_tags($model->risposta));

                        $text = \yii\helpers\StringHelper::truncate(strip_tags($model->risposta),$valTruncate,'... ');

                        if ($length >= $valTruncate) {
                            $modal = $this->render('_modal-answare', [
                                'model' => $model,
                            ]);
                        } else {
                            $modal = '';
                        }

                        return $text . $modal;
                    },
                    'format' => 'raw',
                ],

                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],
    ]);
    ?>
</div>
