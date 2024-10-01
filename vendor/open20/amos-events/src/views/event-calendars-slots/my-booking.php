<?php

use open20\amos\events\AmosEvents;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\grid\ActionColumn;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventCalendars $model
 */
$this->title = \open20\amos\events\AmosEvents::t('amosevents', 'Le mie prenotazioni');
if ($event) {
    $this->title .= ' '.Yii::t('amosevents', 'for event').' "'.$event->title.'"';
}
$this->params['breadcrumbs'][] = $this->title;


echo \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'eventCalendars.title',
            'format' => 'html',
            'label' => AmosEvents::t('amosevents', 'Progetto'),
        ],
        [
            'attribute' => 'date',
            'format' => 'date'
        ],
        [
            'attribute' => 'hour_start',
            'format' => 'time'
        ],
        [
            'attribute' => 'hour_end',
            'value' => function ($model) {
                return $model->getEndHourWithPause();
            },
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{book}',
            'buttons' => [
                'book' => function ($url, $model) {
                    return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('calendar-check-o', [],
                                'dash'), ['/events/event-calendars/view', 'id' => $model->eventCalendars->id],
                            [
                            'class' => 'btn btn-primary',
                            'title' => AmosEvents::t('amosevents', "Gestisci appuntamento"),
                    ]);
                }
            ]
        ]
    ]
]);
