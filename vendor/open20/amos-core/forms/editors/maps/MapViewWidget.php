<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni
 * @category   CategoryName
 */

namespace open20\amos\core\forms\editors\maps;

use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\module\Module;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use open20\amos\core\components\PlacesComponents;

class MapViewWidget extends Widget
{
    const BWStyle = [
        [
            'featureType' => 'water',
            'elementType' => 'geometry',
            'stylers' => [
                [
                    'color' => '#e9e9e9'
                ],
                [
                    'lightness' => 17
                ]
            ]
        ],
        [
            'featureType' => 'landscape',
            'elementType' => 'geometry',
            'stylers' => [
                [
                    'color' => '#f5f5f5'
                ],
                [
                    'lightness' => 20
                ]
            ]
        ],
        [
            'featureType' => 'road.highway',
            'elementType' => 'geometry.fill',
            'stylers' => [
                [
                    'color' => '#ffffff'
                ],
                [
                    'lightness' => 17
                ]
            ]
        ],
        [
            'featureType' => 'road.highway',
            'elementType' => 'geometry.stroke',
            'stylers' => [
                [
                    'color' => '#ffffff'
                ],
                [
                    'lightness' => 29
                ],
                [
                    'weight' => 0.2
                ]
            ]
        ],
        [
            'featureType' => 'road.arterial',
            'elementType' => 'geometry',
            'stylers' => [
                [
                    'color' => '#ffffff'
                ],
                [
                    'lightness' => 18
                ],
            ]
        ],
        [
            'featureType' => 'road.local',
            'elementType' => 'geometry',
            'stylers' => [
                [
                    'color' => '#ffffff'
                ],
                [
                    'lightness' => 16
                ],
            ]
        ],
        [
            'featureType' => 'poi',
            'elementType' => 'geometry',
            'stylers' => [
                [
                    'color' => '#f5f5f5'
                ],
                [
                    'lightness' => 21
                ],
            ]
        ],
        [
            'featureType' => 'poi.park',
            'elementType' => 'geometry',
            'stylers' => [
                [
                    'color' => '#dedede'
                ],
                [
                    'lightness' => 21
                ],
            ]
        ],
        [
            'elementType' => 'labels.text.fill',
            'stylers' => [
                [
                    'color' => '#616161'
                ],
            ]
        ],
        [
            'elementType' => 'labels.icon',
            'stylers' => [
                [
                    'visibility' => 'off'
                ],
            ]
        ],
        [
            'featureType' => 'transit',
            'elementType' => 'geometry',
            'stylers' => [
                [
                    'color' => '#f2f2f2'
                ],
                [
                    'lightness' => 19
                ],
            ]
        ],
        [
            'featureType' => 'administrative',
            'elementType' => 'geometry.fill',
            'stylers' => [
                [
                    'color' => '#fefefe'
                ],
                [
                    'lightness' => 20
                ],
            ]
        ],
        [
            'featureType' => 'administrative',
            'elementType' => 'geometry.stroke',
            'stylers' => [
                [
                    'color' => '#fefefe'
                ],
                [
                    'lightness' => 17
                ],
                [
                    'weight' => 1.2
                ],
            ]
        ],
    ];

    const NoPointStyle = [
        [
            'featureType' => 'poi',
            'stylers' => [
                [
                    'visibility' => 'off'
                ],

            ]
        ],
        [
            'elementType' => 'labels.text.fill',
            'stylers' => [
                [
                    'color' => '#616161'
                ],
            ]
        ],
        [
            'elementType' => 'labels.icon',
            'stylers' => [
                [
                    'visibility' => 'off'
                ],
            ]
        ],
    ];

    const BWPointer = [
        'path' => 'M 0 0 C -8 -19 -10 -22 -10 -30 A 10 10 0 1 1 10 -30 C 10 -22 7 -17 0 0 z M -2 -30 a 2 2 0 1 1 4 0 a 2 2 0 1 1 -4 0',
        'fillColor' => '#000',
        'fillOpacity' => 1,
        'strokeColor' => '#fff',
        'strokeWeight' => 4,
        'scale' => 1.25,
    ];

    /**
     * @var bool $fullWidth
     */
    public $fullWidth = false;

    /**
     * @var array $styles
     */
    public $styles = [];

    /**
     * @var string $centerView
     */
    public $centerView = ''; //auto center and auto zoom   //['lat' => 44.8443156,'lng'=>11.6163367];

    /**
     * @var int $MinZoom
     */
    public $MinZoom = 6;

    /**
     * @var int $zoom
     */
    public $zoom = 12;

    /**
     * @var int $MaxZoom
     */
    public $MaxZoom = 18;

    /**
     * @var string $icona
     */
    public $icona = '';

    /**
     * @var array $markers
     */
    public $markers = [];

    /**
     * @var null $cardTemplate
     */
    public $cardTemplate = null;

    /**
     * @var array $ObjMarkers
     */
    private $ObjMarkers;

    /**
     * @var string $attributeName
     */
    public $attributeName = '';


    /**
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributeName)) {
            throw new \yii\base\InvalidConfigException('Param attributeName must be set');
        }

        $this->ObjMarkers = [];
        $this->defaultCardTemplate();

        foreach ($this->markers as $marker) {
            $pos = PlacesComponents::getGoogleResponseByPlaceIdLatLong($marker[$this->attributeName]);
            $address = PlacesComponents::getGoogleResponseByPlaceId($marker[$this->attributeName], true, true);

            $this->ObjMarkers[] = [
                'latitudine' => $pos[0],
                'longitudine' => $pos[1],
                'icona' => $this->icona,
                'title' => isset($marker['titolo']) ? $marker['titolo'] : '',
                'content' => $address,
                'img' => isset($marker['immagine']) ? $marker['immagine'] : '',
                'link' => isset($marker['link']) ? $marker['link'] : '',
                'label' => isset($marker['label']) ? $marker['label'] : Yii::t('amoscore', 'open')
            ];
        }

        $this->styles = Json::encode($this->styles);
        $this->ObjMarkers = Json::encode($this->ObjMarkers);
        $this->centerView = Json::encode($this->centerView);

        $this->registerAssets();

    }

    /**
     * @return void
     */
    public function run()
    {
        $output = Html::tag('div', '<div id="map"><div id="map-canvas"></div></div>', ['class' => 'map-content']);

        echo $output;
    }

    /**
     * @return void
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        MapViewAsset::register($view);
        $full = $this->fullWidth ? 1 : 0;

        $js = "
        
            $( document ).ready(function() {
                initMap();
                fullMap();
            });

            $( window ).resize(function() {
                fullMap();
            });

            function fullMap(){                   
                if($full){
                    var left = $('#map').offset().left;
                    $('#map-canvas').css('left',-left);
                    $('#map-canvas').css('width','100vw');
                }
            }

            function initMap() {
                var ArrMarkers = $this->ObjMarkers;
                var centerView = $this->centerView;
                var styles = $this->styles;
                var zoom = $this->zoom;
                var min_zoom = $this->MinZoom;
                var max_zoom = $this->MaxZoom;
                var cardTemplate = '$this->cardTemplate';

                MYMAP.init(document.getElementById('map-canvas'),centerView,zoom,min_zoom,max_zoom,ArrMarkers,styles,cardTemplate);
            }

        ";

        $this->view->registerJs($js, View::POS_READY);
    }

    /**
     * @return void
     */
    private function defaultCardTemplate()
    {
        if (!isset($this->cardTemplate)) {
            $this->cardTemplate = '<img src="#img" alt="#title">';
            $this->cardTemplate .= '<div class="marker-card-info">';
            $this->cardTemplate .= '<h5>#title</h5>';
            $this->cardTemplate .= '<p>#content</p>';
            $this->cardTemplate .= '<a href="#link" class="marker-card-link" title="' . BaseAmosModule::t("amoscore", "Vai a {title}", ["title" => "#title"]) . '</a>';
            $this->cardTemplate .= '</div>';
        }
    }

}