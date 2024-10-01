<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\Module;
use app\modules\backendobjects\frontend\blockgroups\NavigazioneGroup;
use app\modules\uikit\BaseUikitBlock;
use trk\uikit\Uikit;
use yii\helpers\ArrayHelper;


class Open2VerticalMenuListBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    protected $component = "open2verticalmenulist";

    public function disable() {
        return 1;
    }

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
        return Yii::t('backendobjects', 'block_module_backend_vertical-menu-list');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'data_usage';
    }

    /**
     * @inheritdoc
     */
    public function admin(array $params = array())
    {   
        
        return "Vertical menu of page.";
    }

    /**
     * 
     * @param array $params
     * @return string
     */
    public function frontend(array $params = array())
    {
        $blockId = $this->getEnvOption('id');
        $params['blockItemId'] = $blockId;
        
        $configs = $this->getValues();
        $configs["id"] = Uikit::unique($this->component);
        $params['data'] = Uikit::configs($configs);
        $params['debug'] = $this->config();
        $params['filters'] = $this->filters;
        
        $params['items'] = Yii::$app->menu->current->with(['hidden'])->children;
        $params['maxlevel'] = $this->getVarValue('level', '');
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

        return ArrayHelper::merge(parent::config(), $configs);
    }
}
