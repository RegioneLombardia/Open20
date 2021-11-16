<?php

/**
 * Lombardia Informatica S.p.A.
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

use yii\web\View;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\events\models\search\EventSearch $model
 * @var string $currentView
 */


/** @var AmosEvents $eventsModule */
$eventsModule = Yii::$app->getModule(AmosEvents::getModuleName());
$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = \open20\amos\events\assets\WizardEventAsset::register($this);

?>
<div class="event-index">
    <?php

    echo $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->session->get('previousUrl')]);

    //    echo $this->render('_search', ['model' => $model,'originAction' => Yii::$app->session->get('previousUrl')]);
    //    echo $this->render('_order', ['model' => $model,'originAction' => Yii::$app->session->get('previousUrl')]);

//    if (\Yii::$app->user->can('EVENT_CREATE')) {
//        echo \yii\helpers\Html::a(AmosEvents::t('amosevents', 'Crea nuovo'), '/events/wizard', ['class' => 'btn btn-primary btn-xs pull-right']);
//    }
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'title',
                'event_type_id' => [
                    'attribute' => 'event_type_id',
                    'label' => $model->getAttributeLabel('eventType'),
                    'value' => 'eventType.title'
                ],
                'status' => [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->getWorkflowBaseStatusLabel();
                    }
                ],
                'begin_date_hour:datetime',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{community}{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('Visualizza', ['/events/event-dashboard/view', 'id' => $model->id], [
                                'class' => 'btn btn-secondary btn-xs ml-1',
                                'title' => AmosEvents::t('amosevents', "Gestisci evento")
                            ]);
                        },
                        'community' => function ($url, $model) {
                            $dg = \open20\amos\events\utility\EventsUtility::getCurrentDg();
                            //if is an operator subscribe as as staff directly
                            if(\open20\amos\events\utility\EventsUtility::isEventCommunityEnabled($model)) {
                                if (\Yii::$app->user->can('SUPER_USER_EVENT') || (\Yii::$app->user->can('EVENT_DG_OPERATOR') && $dg && $model->event_group_referent_id == $dg->id)) {
                                    $url = ['/events/event-dashboard/join-community', 'id' => $model->id];
                                    $canViewCommunity = true;
                                } else {
                                    $url = ['/community/join', 'id' => $model->community_id];
                                    $canViewCommunity = \open20\amos\events\utility\EventsUtility::canViewCommunityEvent($model);
                                }
                                if ($canViewCommunity) {
                                    return Html::a('Community', $url, ['class' => 'btn btn-secondary btn-xs mx-1', 'title' => AmosEvents::t('amosevents', "Vai alla Community")]);
                                }
                            }
                        }
                    ]
                ]
            ],
        ],
        /*'listView' => [
        'itemView' => '_item'
        'masonry' => FALSE,

        // Se masonry settato a TRUE decommentare e settare i parametri seguenti 
        // nel CSS settare i seguenti parametri necessari al funzionamento tipo
        // .grid-sizer, .grid-item {width: 50&;}
        // Per i dettagli recarsi sul sito http://masonry.desandro.com                                     

        //'masonrySelector' => '.grid',
        //'masonryOptions' => [
        //    'itemSelector' => '.grid-item',
        //    'columnWidth' => '.grid-sizer',
        //    'percentPosition' => 'true',
        //    'gutter' => '20'
        //]
        ],
        'iconView' => [
        'itemView' => '_icon'
        ],
        'mapView' => [
        'itemView' => '_map',          
        'markerConfig' => [
        'lat' => 'domicilio_lat',
        'lng' => 'domicilio_lon',
        'icon' => 'iconaMarker',
        ]
        ],*/
        /**  'calendarView' => [
         * 'itemView' => '_calendar',
         * 'options' => [
         * 'lang' => 'it',
         * ],
         * 'eventConfig' => [
         * 'id' => 'id',
         * 'title' => 'eventTitle',
         * 'start' => 'begin_date_hour',
         * 'end' => 'end_date_hour',
         * 'color' => 'eventColor',
         * 'url' => 'eventUrl'
         * ],
         * 'array' => false,//se ci sono più eventi legati al singolo record
         * //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
         * ]*/
    ]); ?>
</div>
