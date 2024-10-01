<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\ContenutoGroup;
use app\modules\uikit\BaseUikitBlock;
use trk\uikit\Uikit;
use Yii;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;

final class MultiMarkersMapBlock extends BaseUikitBlock {

    /**
     * @inheritdoc
     */
    public $cacheEnabled = true;

    public function disable() {
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_multimarkersmap_name');
    }

    public function blockGroup() {
        return ContenutoGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'add_location_alt';
    }

    /**
     * @inheritdoc
     */
    public function config() {
        return [
            'vars' => [
                [
                    'var' => 'idcanvas',
                    'label' => Yii::t('backendobjects', 'Id Canvas'),
                    'type' => self::TYPE_TEXT,
                    'initvalue' => '',
                ],
                [
                    'var' => 'zoom',
                    'label' => Yii::t('backendobjects', 'block_map_zoom_label'),
                    'type' => self::TYPE_SELECT,
                    'initvalue' => '7',
                    'options' => [
                        ['value' => '0', 'label' => Yii::t('backendobjects', 'block_map_zoom_entire')],
                        ['value' => '1', 'label' => '4000 km'],
                        ['value' => '2', 'label' => '2000 km (' . Yii::t('backendobjects', 'block_map_zoom_world') . ')'],
                        ['value' => '3', 'label' => '1000 km'],
                        ['value' => '4', 'label' => '400 km (' . Yii::t('backendobjects', 'block_map_zoom_continent') . ')'],
                        ['value' => '5', 'label' => '200 km'],
                        ['value' => '6', 'label' => '100 km (' . Yii::t('backendobjects', 'block_map_zoom_country') . ')'],
                        ['value' => '7', 'label' => '50 km'],
                        ['value' => '8', 'label' => '30 km'],
                        ['value' => '9', 'label' => '15 km'],
                        ['value' => '10', 'label' => '8 km'],
                        ['value' => '11', 'label' => '4 km'],
                        ['value' => '12', 'label' => '2 km (' . Yii::t('backendobjects', 'block_map_zoom_city') . ')'],
                        ['value' => '13', 'label' => '1 km'],
                        ['value' => '14', 'label' => '400 m (' . Yii::t('backendobjects', 'block_map_zoom_district') . ')'],
                        ['value' => '15', 'label' => '200 m'],
                        ['value' => '16', 'label' => '100 m'],
                        ['value' => '17', 'label' => '50 m (' . Yii::t('backendobjects', 'block_map_zoom_street') . ')'],
                        ['value' => '18', 'label' => '20 m'],
                        ['value' => '19', 'label' => '10 m'],
                        ['value' => '20', 'label' => '5 m (' . Yii::t('backendobjects', 'block_map_zoom_house') . ')'],
                        ['value' => '21', 'label' => '2.5 m'],
                    ],
                ],
                [
                    'var' => 'maptype',
                    'label' => Yii::t('backendobjects', 'block_map_maptype_label'),
                    'type' => self::TYPE_SELECT,
                    'initvalue' => 'roadmap',
                    'options' => [
                        ['value' => 'roadmap', 'label' => Yii::t('backendobjects', 'block_map_maptype_roadmap')],
                        ['value' => 'satellite', 'label' => Yii::t('backendobjects', 'block_map_maptype_satellitemap')],
                        ['value' => 'hybrid', 'label' => Yii::t('backendobjects', 'block_map_maptype_hybrid')],
                    ],
                ],
                [
                    'var' => 'points',
                    'label' => Yii::t('backendobjects', 'block_map_items_label'),
                    'type' => self::TYPE_MULTIPLE_INPUTS,
                    'options' => [
                        [
                            'var' => 'address',
                            'label' => Yii::t('backendobjects', 'block_map_address_label'),
                            'type' => self::TYPE_TEXT,
                            'placeholder' => ''
                        ], [
                            'var' => 'description',
                            'label' => Yii::t('backendobjects', 'block_map_description_label'),
                            'type' => self::TYPE_TEXT,
                            'placeholder' => ''
                        ]
                    ],
                ],
                [
                    'var' => 'visibility',
                    'label' => 'Visibilità del blocco',
                    'description' => 'Imposta la visibilità della sezione.',
                    'initvalue' => '',
                    'type' => 'zaa-select', 'options' => [
                        ['value' => '', 'label' => 'Visibile a tutti'],
                        ['value' => 'guest', 'label' => 'Visibile solo ai non loggati'],
                        ['value' => 'logged', 'label' => 'Visibile solo ai loggati'],
                    ],
                ],
                [
                    'var' => 'addclass',
                    'label' => 'Visibilità per profilo',
                    'description' => 'Imposta la visibilità della sezione in base al profilo dell\'utente loggato',
                    'type' => 'zaa-multiple-inputs',
                    'options' => [
                        [
                            'var' => 'class',
                            'type' => 'zaa-select',
                            'initvalue' => '',
                            'options' => BaseUikitBlock::getClasses(),
                        ]
                    ],
                ],
            ],
            'cfgs' => [
                ['var' => 'snazzymapsUrl', 'label' => Yii::t('backendobjects', 'block_map_snazzymapsUrl_label'), 'type' => self::TYPE_TEXT],
                ['var' => 'mapsKey', 'label' => Yii::t('backendobjects', 'block_map_api_key'), 'type' => self::TYPE_TEXT],
            ]
        ];
    }

    public function getFieldHelp() {
        return [
            'snazzymapsUrl' => Yii::t('backendobjects', 'block_map_snazzymapsUrl_help'),
            'mapsKey' => Yii::t('backendobjects', 'block_map_api_key_help'),
        ];
    }

    public function frontend(array $params = array()) {

        $blockId = $this->getEnvOption('id');
        $params['blockItemId'] = $blockId;

        $configs = $this->getValues();
        $configs["id"] = Uikit::unique($this->component);
        $params['data'] = Uikit::configs($configs);

        return $this->view->render($this->getViewFileName('php'), $params, $this);
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        return $this->frontend();
    }

}
