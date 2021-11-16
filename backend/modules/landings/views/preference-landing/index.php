<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/landings/views
 */

use yii\widgets\Pjax;
use backend\modules\landings\Module;
use open20\amos\core\helpers\Html;
use open20\amos\layout\utility\AmosIconsBi;
use open20\amos\core\views\DataProviderView;
use backend\modules\campains\models\PreferenceCampainSection;
use backend\modules\landings\utility\PreferenceLandingUtility;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\modules\landings\models\search\PreferenceLandingSearch $model
 */

$this->title = Yii::t('amoscore', 'Landing pages');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/landings']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preference-landing-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'title',
                [
                    'label' => Module::t('preferencelandings', "Creato da"),
                    'value' => function ($model) {
                        $creatorProfile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $model->created_by])->one();
                        if ($creatorProfile) {
                            return $creatorProfile->nomeCognome;
                        }
                    }
                ],
                [
                    'label' => Module::t('preferencelandings', "Struttura"),
                    'attribute' => 'preferenceStructure.name'
                ],
                [
                    'label' => Module::t('preferencelandings', "Creato il"),
                    'attribute' => 'created_at',
                    'format' => 'date'
                ],
                [
                    'label' => Module::t('preferencelandings', "Stato"),
                    'value' => function ($model) {
                        return $model->labelStatus;
                    }

                ],

                [
                    'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                    'template' => '{landing}{update}{delete}',
                    'buttons' => [

                        'update' => function ($url, $model) use ($event) {
                            return Html::a(AmosIconsBi::show('ic_edit'), $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => Module::t('preferencelandings', 'Modifica')], true);
                        },
                        'landing' => function ($url, $model) use ($event) {
                            if ($model->status == \backend\modules\landings\models\PreferenceLanding::PREFERENCE_LANDING_STATUS_PUBLISHED) {

                                return Html::a(AmosIconsBi::show('ic_web'), \backend\modules\landings\utility\PreferenceLandingUtility::getUrlLanding($model), [
                                    'class' => 'btn btn-xs btn-icon',
                                    'data-toggle' => 'tooltip',
                                    'title' => Module::t('preferencelandings', 'Vai alla landing'),
                                    'target' => '_blank'
                                ]);
                            }
                        },
//                        'preview' => function ($url, $model) use ($event) {
//                                return Html::a(AmosIconsBi::show('ic_search'), \backend\modules\landings\utility\PreferenceLandingUtility::getUrlLandingPreview($model), [
//                                    'class' => 'btn btn-xs btn-icon',
//                                    'data-toggle' => 'tooltip',
//                                    'title' => Module::t('preferencelandings', 'Preview landing'),
//                                    'target' => '_blank'
//                                ]);
//                        },

                        'delete' => function ($url, $model) use ($event) {

                            // check if exist PreferenceCampain for landing page

                            $text_data_confirm = "Sei sicuro di eliminare la landing? \r\n";
                            
                            if( null == $preference_campains = PreferenceLandingUtility::getPreferenceCampainContainer($model) ){
                                $text_data_confirm .= "La landing non risulta ancora associata a nessuna campagna.";
                            }else{

                                $text_data_confirm .= "La landing risulta associata alle seguenti campagne: <ul>";

                                foreach ($preference_campains as $value) {

                                    
                                    $text_data_confirm .= "<li><b>".$value->titolo."</b></li>";
                                }

                                $text_data_confirm .= "</ul>";
                                
                            }

                            return Html::a(AmosIconsBi::show('ic_close', 'danger'), $url, 
                                [
                                    'class' => 'btn btn-xs btn-icon', 
                                    'data-toggle' => 'tooltip', 
                                    'title' => Module::t('preferencelandings', 'Elimina'),
                                    'data-confirm' => $text_data_confirm
                                ], 
                            true);
                        },
                    ]
                ]
            ],
        ],
        /*'listView' => [
        'itemView' => '_item',
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
        'icon' => 'iconMarker',
        ]
        ],
        'calendarView' => [
        'itemView' => '_calendar',
        'clientOptions' => [
        //'lang'=> 'de'
        ],
        'eventConfig' => [
        //'title' => 'titleEvent',
        //'start' => 'data_inizio',
        //'end' => 'data_fine',
        //'color' => 'colorEvent',
        //'url' => 'urlEvent'
        ],
        'array' => false,//se ci sono più eventi legati al singolo record
        //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
        ]*/
    ]); ?>

</div>
