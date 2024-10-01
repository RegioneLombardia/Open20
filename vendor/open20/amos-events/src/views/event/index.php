<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\controllers\EventController;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\events\models\search\EventSearch $model
 * @var string $currentView
 */
$js = "
$('.reject-btns').on('click', function(event) {
    event.preventDefault();
    var hrefValue = $(this).attr('href');
    var visibleInCalendar = confirm(\"" . AmosEvents::t(
                'amosevents',
                "Is the event still to be visible in the calendar even during the edit"
        ) . "?\");
    this.href = hrefValue + '&visibleInCalendar=' + (visibleInCalendar ? 1 : 0);
    window.location.href = this.href;
});
";
$this->registerJs($js, View::POS_READY);

/** @var AmosEvents $eventsModule */
$eventsModule = Yii::$app->getModule(AmosEvents::getModuleName());

/** @var EventController $appController */
$appController = Yii::$app->controller;
$param = (isset($addActionColumns) ? $addActionColumns : null);
$actionColumn = ($eventsModule->enableExportToPdfInColumn ? '{esportapdf}' : '') . ($eventsModule->enableExportToWordInColumn ? '{esportadocx}' : '') . $appController->getGridViewActionColumnsTemplate($param);
?>

<div class="event-index">
    <?php
    echo $this->render('_search', [
        'model' => $model,
        'originAction' => Yii::$app->session->get('previousUrl')
    ]);

    echo $this->render('_order', [
        'model' => $model,
        'originAction' => Yii::$app->session->get('previousUrl')
    ]);

    echo $this->render('_legenda', [
        'model' => $model,
        'model_type' => $eventTypeList,
        'eventsModule' => $eventsModule,
        'originAction' => Yii::$app->session->get('previousUrl')
    ]);

    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'title' => [
                    'attribute' => 'title',
                    'label' => $model->getAttributeLabel('title'),
                ],
                'eventType' => [
                    'attribute' => 'eventType.title',
                    'label' => $model->getAttributeLabel('eventType'),
                    'value' => 'eventType.title'
                ],
                'begin_date_hour:datetime' => [
                    'attribute' => 'begin_date_hour',
                    'label' => $model->getAttributeLabel('begin_date_hour'),
                    'format' => ['date', 'php:d/m/Y H:i:s'],
                ],
                'end_date_hour:datetime' => [
                    'attribute' => 'end_date_hour',
                    'label' => $model->getAttributeLabel('end_date_hour'),
                    'format' => ['date', 'php:d/m/Y H:i:s'],
                    'visible' => $eventsModule->enableAgid
                ],
                'status' => [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        /** @var \open20\amos\events\models\Event $model */
                        return $model->getWorkflowBaseStatusLabel();
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => $actionColumn,
                    'buttons' => [
                        'duplicateBtn' => function ($url, $model) {
                            /** @var \open20\amos\events\models\Event $model */
                            if (Yii::$app->user->can('EVENT_UPDATE', ['model' => $model])) {
                                /** @var EventController $controller */
                                $controller = Yii::$app->controller;
                                return ModalUtility::addConfirmRejectWithModal([
                                            'modalId' => 'duplicate-content-modal-id-' . $model->id,
                                            'modalDescriptionText' => AmosEvents::t('amosevents', '#duplicate_content_modal_text'),
                                            'btnText' => AmosIcons::show('copy', ['class' => '']),
                                            'btnLink' => Url::to([
                                                '/'
                                                . $controller->module->id
                                                . '/'
                                                . $controller->id
                                                . '/duplicate-content',
                                                'id' => $model->id
                                            ]),
                                            'btnOptions' => [
                                                'title' => AmosEvents::t(
                                                        'amosevents',
                                                        '#duplicate_content'
                                                ), 'class' => 'btn btn-tools-secondary'
                                            ]
                                ]);
                            }
                        },
                        'publish' => function ($url, $model) {
                            /** @var \open20\amos\events\models\Event $model */
                            if (
                                    Yii::$app->getUser()->can('EVENTS_VALIDATOR') || Yii::$app->getUser()->can('PLATFORM_EVENTS_VALIDATOR')
                            ) {
                                $createUrlParams = [
                                    '/events/event/validate',
                                    'id' => $model['id']
                                ];

                                return Html::a(
                                                AmosIcons::show(
                                                        'check-circle',
                                                        ['class' => 'btn btn-tool-secondary']
                                                ),
                                                Yii::$app->urlManager->createUrl($createUrlParams),
                                                [
                                                    'title' => AmosEvents::t('amosevents', 'Publish')
                                                ]
                                );
                            }
                        },
                        'reject' => function ($url, $model) {
                            /** @var \open20\amos\events\models\Event $model */
                            if (
                                    Yii::$app->getUser()->can('EVENTS_VALIDATOR') || Yii::$app->getUser()->can('PLATFORM_EVENTS_VALIDATOR')
                            ) {
                                $createUrlParams = [
                                    '/events/event/reject',
                                    'id' => $model['id']
                                ];
                                return Html::a(
                                                AmosIcons::show(
                                                        'minus-circle',
                                                        ['class' => 'btn btn-tool-secondary']
                                                ),
                                                Yii::$app->urlManager->createUrl($createUrlParams),
                                                [
                                                    'title' => AmosEvents::t(
                                                            'amosevents',
                                                            'Reject'
                                                    ),
                                                    'class' => 'reject-btns'
                                                ]
                                );
                            }
                        },
                        'community' => function ($url, $model) {
                            /** @var \open20\amos\events\models\Event $model */
                            if (isset($model->community_id)) {
                                $createUrlParams = [
                                    '/community/join',
                                    'id' => $model['community_id']
                                ];

                                return Html::a(
                                                AmosIcons::show(
                                                        'group',
                                                        ['class' => 'btn btn-tool-secondary']
                                                ),
                                                Yii::$app->urlManager->createUrl($createUrlParams),
                                                [
                                                    'title' => AmosEvents::t('amosevents', 'Join the community')
                                                ]
                                );
                            }
                        },
                        'update' => function ($url, $model) {
                            /** @var \open20\amos\events\models\Event $model */
                            $createUrlParams = [
                                '/events/event/update',
                                'id' => $model->id
                            ];
                            if (Yii::$app->user->can('EVENT_UPDATE', ['model' => $model]) && $model->status == Event::EVENTS_WORKFLOW_STATUS_DRAFT) {


                                return Html::a(
                                                AmosIcons::show('edit'),
                                                Yii::$app->urlManager->createUrl($createUrlParams),
                                                [
                                                    'title' => AmosEvents::t('amosevents', 'Modifica'),
                                                    'class' => 'btn btn-tool-secondary'
                                                ]
                                );
                            }
                            if (Yii::$app->user->can('EVENT_VALIDATOR', ['model' => $model])) {
                                return Html::a(
                                                AmosIcons::show('edit'),
                                                Yii::$app->urlManager->createUrl($createUrlParams),
                                                [
                                                    'title' => AmosEvents::t('amosevents', 'Modifica'),
                                                    'class' => 'btn btn-tool-secondary'
                                                ]
                                );
                            }
                        },
                        'esportapdf' => function ($url, $model) use ($eventsModule) {
                            if ($eventsModule->enableExportToPdfInColumn == false) {
                                return '';
                            }
                            /** @var \open20\amos\events\models\Event $model */
                            $createUrlParams = [
                                '/events/event/esporta',
                                'id' => $model->id,
                                'format' => 'pdf',
                            ];

                            return Html::a(
                                    AmosIcons::show('file-pdf-o', [], 'dash'),
                                    Yii::$app->urlManager->createUrl($createUrlParams),
                                    [
                                        'title' => AmosEvents::t('amosevents', 'Esporta in PDF'),
                                        'class' => 'btn btn-tool-primary'
                                    ]
                            );
                        },
                        'esportadocx' => function ($url, $model) use ($eventsModule) {
                            if ($eventsModule->enableExportToWordInColumn == false) {
                                return '';
                            }
                            /** @var \open20\amos\events\models\Event $model */
                            $createUrlParams = [
                                '/events/event/esporta',
                                'id' => $model->id,
                                'format' => 'docx',
                            ];

                            return Html::a(
                                    AmosIcons::show('file-word-o', [], 'dash'),
                                    Yii::$app->urlManager->createUrl($createUrlParams),
                                    [
                                        'title' => AmosEvents::t('amosevents', 'Esporta in WORD'),
                                        'class' => 'btn btn-tool-primary'
                                    ]
                            );
                        },
                    ]
                ]
            ],
            'enableExport' => $eventsModule->enableExport
        ],
        'iconView' => [
            'itemView' => '_icon'
        ],
        'calendarView' => [
            'itemView' => '_calendar',
            'options' => [
                'lang' => 'it',
            ],
            /* 'eventConfig' => [
              'id' => 'id',
              'title' => 'eventTitle',
              'start' => 'begin_date_hour',
              'end' => 'end_date_hour',
              'color' => 'eventColor',
              'url' => 'eventUrl'
              ], */
            'clientOptions' => [
                'eventClick' => new \yii\web\JsExpression("function(event, jsEvent, view) {                   
                    $('#modalTitle').html(event.title);
                   // $('#modalBody').html(event.nonstandard.description);
                    $('#eventUrl').attr('href',event.url);
                    //$('#calendarModal').modal();
                }"),
            ],
            'ajaxEvents' => \yii\helpers\Url::to(['/events/event/jsoncalendar']),
            'array' => false, //se ci sono più eventi legati al singolo record
        //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
        ]
    ]);
    ?>
    <div id="calendarModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                    <h4 id="modalTitle" class="modal-title"></h4>
                </div>
                <div id="modalBody" class="modal-body"> 

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= AmosEvents::txt('Chiudi') ?></button>
                    <a href="#" id="eventUrl" type="button" class="btn btn-default"><?= AmosEvents::txt('Visualizza') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
