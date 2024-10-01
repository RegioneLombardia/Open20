<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use app\modules\backendobjects\frontend\blockgroups\NavigazioneGroup;
use trk\uikit\Uikit;
use yii\helpers\ArrayHelper;
use open20\cms\dashboard\utilities\Utility;


class Open2SiteMapBlock extends BaseUikitBlock
{

    public $cacheEnabled = true;

    public function init()
    {
        parent::init();
        
    }
    /**
     * @inheritdoc
     */
    protected $component = "open2sitemap";

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return NavigazioneGroup::className();
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_backend_sitemap');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'sitemap-outline';
    }

    /**
     * @inheritdoc
     */
    public function admin(array $params = array())
    {
        return $this->frontend();
    }

    public function frontend(array $params = array())
    {
       if (!Uikit::element('data', $params, '')) {
            $configs        = $this->getValues();
            $configs["id"]  = Uikit::unique($this->component);
            $params['data'] = Uikit::configs($configs);
        }
        return $this->view->render($this->getViewFileName('php'), $params, $this);
    }
    
    public function config() {
        $configs = [
            'vars' => [
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
        ];


        $params = ArrayHelper::merge(parent::config(), $configs);
        
        $containers = Utility::getAllCmsContainer();
        
        foreach($params as $param => $p){
            if($param == 'vars'){
                foreach($p as $k => $array){
                    if(isset($array['var']) && $array['var'] == 'container'){
                        
                        if(isset($array['options'])){
                            $options = [];
                            foreach ($containers as $localcontainer) {
                                $options[] = [
                                    'value' =>$localcontainer['alias'],
                                    'label' =>$localcontainer['name'],
                                ];
                            }  
                            $params[$param][$k]['options'] = $options;
                        }
                       
                        break 2;
                    }
                }
            }
        }
        
        return $params;
    }

    
}