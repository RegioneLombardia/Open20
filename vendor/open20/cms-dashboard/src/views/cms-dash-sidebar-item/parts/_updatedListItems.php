<?php

use open20\amos\core\views\DataProviderView;
use open20\amos\core\helpers\Html;
use open20\cms\dashboard\Module;

?>

<div id="listSidebarItems" class="cms-dash-sidebar-item-index">
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'position',
                'icon_name' => [
                    'attribute' => 'icon_name',
                    'format' => 'html',
                    'label' => Yii::t('amosdashboards', 'Icon'),
                    'value' => function($model){
                        return '<span class="mdi mdi-' . $model->icon_name . ' icon-sidebar"></span>';
                    }
                ],
                'label',
                'description',
                'link',
                'isVisible' => [
                    'attribute' => 'isVisible',
                    'format' => 'html',
                    //'label' => '',
                    'value' => function($model){
                        return ($model->isVisible) ? Yii::t('amosdashboards', 'Si') : Yii::t('amosdashboards', 'No');
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{sopra}{sotto}{update}{delete}',
                    'buttons' => [
                        'sopra' => function ($url, $model) {
                            $options = ['id' => 'sidebarItemUp-' . $model->id, 'name' => $model->position, 'title' => Yii::t('amosdashboards', 'Sposta sopra'), 'data-pjax' => '0'];
                            if($model->position > 1)
                                return Html::a('Su', [], $options);
                            else return '';
                        },
                        'sotto' => function ($url, $model) use ($dataProvider){
                            $options = ['id' => 'sidebarItemDown-' . $model->id, 'name' => $model->position, 'title' => Yii::t('amosdashboards', 'Sposta sotto'), 'data-pjax' => '0'];
                            if($model->position < $dataProvider->getCount())
                                return Html::a('Sotto', [], $options);
                            else return '';
                        },
                        'delete' => function($url, $model){
                            return Html::a('<span class="mdi mdi-delete"></span>', Yii::$app->urlManager->createUrl([
                                    '/' . Module::getModuleName() . '/cms-dash-sidebar-item/delete',
                                    'id' => $model->id,
                                ]),
                                [
                                    'title' => Module::t('amosdashboards', 'Elimina'),
                                    'class' => 'btn btn-danger-inverse',
                                    'style' => 'color:#a61919; border-top:1px solid #ccc',
                                    'data-confirm' => Module::t('amosdahboards', '#deleteConfirm')
                                ]);
                        }
                    ]
                ],
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