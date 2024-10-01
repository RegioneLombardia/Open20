<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\backendobjects\frontend\blockgroups\LayoutInPaginaGroup;
use app\modules\uikit\BaseUikitBlock;
use yii\helpers\ArrayHelper;
use trk\uikit\Uikit;


final class SezioneOrizzontaleBlock extends BaseUikitBlock
{
    public $isContainer = true;
    public $cacheEnabled = false;
    /**
     * @inheritdoc
     */
    protected $component = "sezioneorizzontale";

    public function config(){
        $uikitModule = Yii::$app->getModule('uikit'); 
        if($uikitModule->hidePermissionRole){
            $configs = [
                'cfgs' => [
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
            
        }else{
            $configs = [
                'cfgs' => [
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
                    [
                        'var' => 'rbac_permissions',
                        'label' => 'Ruoli utente',
                        'description' => 'Seleziona i ruoli degli utenti loggati che possono visualizzare la sezione.',
                        'type' => 'zaa-multiple-inputs',
                        'options' => [
                            [
                                'var' => 'rbac_permission',
                                'type' => 'zaa-text',
                                'initvalue' => '',
                            ]
                        ],
                    ],
                ],
            ];
        }
        $config = ArrayHelper::merge(parent::config(), $configs);
        $this->cacheEnabled = $this->getVarValue('cache');
        
        return $config;
    }
    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_backend_sezioneorizzontale');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'crop-landscape';
    }

    /**
     * @inheritdoc
     */
    protected function getPlaceholders()
    {
        return [
            ['var' => 'content', 'cols' => $this->getExtraValue('content')]
        ];
    }

    public function extraVars()
    {
        $this->extraValues['content'] = 12;
        return parent::extraVars();
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return LayoutInPaginaGroup::class;
    }
}