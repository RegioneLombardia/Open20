<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use trk\uikit\Uikit;
use Yii;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;



final class CountDownBlock extends BaseUikitBlock {
    /**
     * @inheritdoc
     */
    public $cacheEnabled = true;
    
    /**
     * @inheritdoc
     */
    protected $component = "countdown";

    /**
     * @inheritdoc
     */
    
    public function disable(){
        return 0;
    }
    
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_countdown_name');
    }
    
    public function blockGroup()
    {
        return LegacyGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'hourglass_bottom';
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
    public function admin()
    {
        return $this->frontend();
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
