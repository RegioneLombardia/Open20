<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-events/src/views
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\events\AmosEvents;
use open20\amos\core\icons\AmosIcons;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventCalendars $model
 */
$urlGet = filter_input(INPUT_GET, 'url');
$this->params['urlget'] = $urlGet;
$this->title = \open20\amos\events\AmosEvents::t('amosevents', 'Calendar');
if ($model->event_id) {
    $this->title .= ' ' . Yii::t('amosevents', 'for event') . ' "' . \Yii::$app->formatter->asHtml($event->title) . '"';
}
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Event Calendars'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$hasUserBookedSlot = $model->hasUserBookedSlot(\Yii::$app->user->id);
$isLoggedUserPartner = \open20\amos\events\utility\EventsUtility::isLoggedUserPartner($model->id);;
$canViewUser = \Yii::$app->user->can('ADMIN') || $isLoggedUserPartner;


?>
<div class="col-xs-12 nop event-calendars-view">

    <div class="col-xs-12 nop m-b-25">

        <div class="col-xs-12 col-sm-12 col-md-5">
            <small><?= AmosEvents::t('amosevents', 'Gruppo') . ' ' . $model->group ?></small>
            <h2><?= AmosEvents::t('amosevents', 'Progetto') . ' ' . '<strong>' . $model->title . '</strong>' ?></h2>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-2">
            <label><?= AmosEvents::t('amosevents', 'Data inizio') ?></label>
            <h3 class="m-t-0"><?= '<strong>' . Yii::$app->formatter->asDate($model->date_start) . '</strong>' ?></h3>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <label><?= AmosEvents::t('amosevents', 'Orari') ?></label>
            <h3 class="m-t-0"><?= 'dalle <strong>' . Yii::$app->formatter->asTime($model->hour_start, 'HH:mm') . '</strong> alle <strong>' . Yii::$app->formatter->asTime($model->hour_end, 'HH:mm') . '</strong>' ?></h3>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-2">
            <label><?= AmosEvents::t('amosevents', 'Slot') ?></label>
            <h3 class="m-t-0"><?= ($model->slot_duration - $model->break_time) . ' min.' ?></h3>
        </div>

        <div class="col-xs-12 description">
            <?= $model->description ?>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="col-xs-12 nop gestione-slot-legenda m-t-20 m-b-20">
            <div class="col-xs-12 col-sm-12 col-md-6 nop m-t-10">
                <h3><?= AmosEvents::t('amosevents', 'Elenco slot del progetto') ?></h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 nop m-t-10">
                <div class="col-xs-6 nop">
                    <?= AmosIcons::show('calendar-check-o', [], 'dash') . ' ' . AmosEvents::t('amosevents', 'Prenota appuntamento') ?>
                </div>
                <div class="col-xs-6 nop">
                    <?= AmosIcons::show('close') . ' ' . AmosEvents::t('amosevents', 'Rimuovi appuntamento') ?>
                </div>
            </div>
        </div>


        <?= \open20\amos\core\views\AmosGridView::widget([
            'dataProvider' => $dataProviderSlots,
            'formatter' => [
                'class' => 'yii\i18n\Formatter',
                'nullDisplay' => '-'
            ],
            'columns' => [
                [
                    'attribute' => 'date',
                    'format' => 'date'
                ],
                [
                    'attribute' => 'hour_start',
                    'value' => function ($model) {
                        return \Yii::$app->formatter->asTime($model->hour_start, 'php:H:i');
                    }
                ],
                [
                    'attribute' => 'hour_end',
                    'value' => function ($model) {
                        return $model->getEndHourWithPause();
                    },
                ],
                [
                    'value' => function ($model) {
                        $available = $model->eventCalendars->max_participant - $model->getEventCalendarsSlotsBooked()->count();
                        if ($available == 0) {
                            return AmosEvents::t('amsoevents', 'Posti esauriti');
                        }
                        return $available;

                    },
                    'label' => AmosEvents::t('amsoevents', 'Posti disponibili')
                ],

//                [
//                    'attribute' => 'user',
//                    'value' => function ($model) use ($canViewUser) {
////                            $user = '';
////                            if ($canViewUser || $model->user->id == \Yii::$app->user->id) {
////                                $user = $model->user->userProfile->nomeCognome;
////                            }
//                        return $model->getStatusSlot();
//                    },
//                    'label' => AmosEvents::t('amosevents', 'Stato prenotazione')
//                ],
//                [
//                    'attribute' => 'affiliation',
//                    'visible' => $isLoggedUserPartner
//                ],
//                [
//                    'attribute' => 'cellphone',
//                    'visible' => $isLoggedUserPartner
//                ],
                [
                    'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                    'controller' => 'event-calendars-slots',
                    'template' => '{users}{unbook}{book}',
                    'buttons' => [
                        'users' => function ($url, $model) use ($canViewUser) {
                            if ($canViewUser) {
                                return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('accounts'),
                                    ['/events/event-calendars-slots/booked-users', 'id' => $model->id, 'url' => \Yii::$app->getView()->params['urlget']],
                                    [
                                        'class' => 'btn btn-primary', //                                            'data-toggle' => 'modal',
//                                            'data-target' => '#modal-extra-info',
                                        'title' => AmosEvents::t('amosevents', "Mostra prenotazioni"),
                                    ]);
                            }
                        },
                        'book' => function ($url, $model) use ($hasUserBookedSlot) {
                            if ($model->canBook()) {
                                return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('calendar-check-o',
                                    [], 'dash'),
                                    ['/events/event-calendars-slots/book-slot', 'id' => $model->id, 'url' => \Yii::$app->getView()->params['urlget']],
                                    [
                                        'class' => 'btn btn-primary button-book-slot',
                                        'data-key' => $model->id,
//                                            'data-toggle' => 'modal',
//                                            'data-target' => '#modal-extra-info',
                                        'title' => AmosEvents::t('amosevents', "Prenota appuntamento"),
                                    ]);
                            }
                        },
                        'unbook' => function ($url, $model) {
                            if ($model->isBookedByUser(\Yii::$app->user->id)) {
                                return \yii\helpers\Html::a(AmosIcons::show('close'),
                                    ['/events/event-calendars-slots/unbook-slot', 'id' => $model->id, 'url' => \Yii::$app->getView()->params['urlget']],
                                    [
                                        'class' => 'btn btn-primary',
                                        'data-confirm' => AmosEvents::t('amosevents',
                                            "Sei sicuro di voler annullare l'appuntamento?"),
                                        'title' => AmosEvents::t('amosevents', "Annulla appuntamento"),
                                    ]);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (empty($model->user_id)) {
                                return \yii\helpers\Html::a(AmosIcons::show('delete'), $url,
                                    [
                                        'class' => 'btn btn-danger-inverse',
                                        'data-confirm' => AmosEvents::t('amosevents',
                                            "Sei sicuro di eliminare questo slot?"),
                                        'title' => AmosEvents::t('amosevents', "Elimina slot"),
                                    ], true);
                            }
                        }
                    ]
                ]
            ]
        ])
        ?>
    </div>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?=
    Html::a(Yii::t('amoscore', 'Chiudi'),
        (!empty($urlGet) ? $urlGet : ['/events/event/view', 'id' => $model->event_id, '#' => 'tab-calendars']),
        ['class' => 'btn btn-secondary']);
    ?>
</div>

<?php echo $this->render('_modal_additional_info'); ?>
