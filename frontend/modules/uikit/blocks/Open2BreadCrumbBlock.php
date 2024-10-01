<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\Module;
use app\modules\backendobjects\frontend\blockgroups\NavigazioneGroup;
use app\modules\uikit\BaseUikitBlock;
use yii\helpers\ArrayHelper;




class Open2BreadCrumbBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    protected $component = "open2breadcrumb";

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
        return Yii::t('backendobjects', 'block_module_backend_breadcrumb');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'arrow-right';
    }

    /**
     * @inheritdoc
     */
    public function admin(array $params = array())
    {   $str_view = '<img class="w-auto" src="/img/breadcrumb/breadcrumb.png">';

        return $str_view ;
        //return $this->frontend();
       // return "Modulo dinamico che mostra l'alberatura della pagina attraverso un menù navigabile.";
    }

    /**
     * 
     * @param array $params
     * @return string
     */
    public function frontend(array $params = array())
    {
        
        
        $params['items'] = Yii::$app->menu->current->with(['hidden'])->teardown;
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
