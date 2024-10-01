<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\Module;
use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\ListatoContenutiGroup;
use yii\helpers\ArrayHelper;


final class Open2TabsPanelBlock extends BaseUikitBlock
{
    
    
    protected $component = "open2tabspanel";
    
    
    //put your code here
    public function admin() 
    {
        if(count($this->getVarValue('items', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('Inserisci delle tab per visualizzare il contenuto.') . '</span></div>';
        }
    }

    public function name() {
        return Yii::t('backendobjects', 'block_module_backend_tabspanel');
    }

   /**
     * @inheritDoc
     */
    public function blockGroup()
    {
        return ListatoContenutiGroup::class;
    }

    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'tab';
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
