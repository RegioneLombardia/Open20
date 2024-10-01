<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\ContenutoGroup;
use yii\helpers\ArrayHelper;

/**
 * Google Maps Block.
 *
 * @since 1.0.0
 */
final class MapBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    public $cacheEnabled = true;

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_map_name');
    }
    
    public function blockGroup()
    {
        return ContenutoGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'map';
    }

    /**
     * @inheritdoc
     */
    public function config()
    {
        return [
            'vars' => [
                [
                    'var' => 'address',
                    'label' => Yii::t('backendobjects', 'block_map_address_label'),
                    'description' => Yii::t('backendobjects', 'Inserisci l\'indirizzo dove mettere il pin sulla mappa. Deve avere il formato Via + nome della via + nome della città.'),
                    'type' => self::TYPE_TEXT,
                    'placeholder' => 'Via Bologna 549, Ferrara'
                ],
                [
                    'var' => 'zoom',
                    'label' => Yii::t('backendobjects', 'block_map_zoom_label'),
                    'type' => self::TYPE_SELECT,
                    'description' => Yii::t('backendobjects', 'Definisci lo zoom da impostare a partire dal pin sulla mappa.'),
                    'initvalue' => '12',
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
                    'description' => Yii::t('backendobjects', 'Seleziona la tipologia di mappa da visualizzare.'),
                    'options' => [
                        ['value' => 'm', 'label' => Yii::t('backendobjects', 'block_map_maptype_roadmap')],
                        ['value' => 'k', 'label' => Yii::t('backendobjects', 'block_map_maptype_satellitemap')],
                       // ['value' => 'h', 'label' => Yii::t('backendobjects', 'block_map_maptype_hybrid')],
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
                [
                    'var' => 'snazzymapsUrl', 
                    'label' => Yii::t('backendobjects', 'block_map_snazzymapsUrl_label'), 
                    'description' => Yii::t('backendobjects', 'Configurazione tecnica per Snazzy Maps iFrame. Non è obbligatoria la compilazione.'),
                    'type' => self::TYPE_TEXT
                ],
                [
                    'var' => 'mapsKey', 
                    'label' => Yii::t('backendobjects', 'block_map_api_key'), 
                    'description' => Yii::t('backendobjects', 'Configurazione tecnica per Maps API Key. Non è obbligatoria la compilazione.'),
                    'type' => self::TYPE_TEXT
                ],
            ]
        ];
    }

    public function getFieldHelp()
    {
        return [
            'snazzymapsUrl' => Yii::t('backendobjects', 'block_map_snazzymapsUrl_help'),
            'mapsKey' => Yii::t('backendobjects', 'block_map_api_key_help'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function extraVars()
    {
        return [
            'embedUrl' => $this->generateEmbedUrl(),
        ];
    }

    /**
     * Generate the embed url based on localisation or whether its snazzy or not.
     * @since 1.0.2
     */
    public function generateEmbedUrl()
    {
        if (($snazzymapsUrl = $this->getCfgValue('snazzymapsUrl'))) {
            return $snazzymapsUrl;
        }
        
        if (empty($this->getVarValue('address'))) {
            return false;
        }
        
        $params = [
            'f' => 'q',
            'source' => 's_q',
            'hl' => Yii::$app->composition->langShortCode,
            'q' => $this->getVarValue('address', 'Zephir Software Design AG, Tramstrasse 66, Münchenstein'),
            'z' => $this->getVarValue('zoom', 15),
            't' => $this->getVarValue('maptype', 'h'),
            'output' => 'embed',
            'key' => $this->getCfgValue('mapsKey'),
        ];
        
        return 'https://maps.google.com/maps?' . http_build_query(array_filter($params), null, '&', PHP_QUERY_RFC1738);
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        return '{% if vars.address is not empty %}<div class="iframe-container"><iframe src="{{extras.embedUrl | raw}}"></iframe></div>{% else %}<span class="block__empty-text">' . Yii::t('backendobjects', 'block_map_no_content') . '</span>{% endif %}';
    }
}
