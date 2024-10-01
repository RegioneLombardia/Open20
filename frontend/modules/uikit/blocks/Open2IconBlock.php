<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use luya\cms\frontend\blockgroups\DevelopmentGroup;
use trk\uikit\Uikit;
use app\modules\backendobjects\frontend\blockgroups\ContenutoGroup;
use yii\helpers\ArrayHelper;



class Open2IconBlock extends BaseUikitBlock
{
    public $cacheEnabled = true;

    /**
     * @inheritdoc
     */
    protected $component = "open2icon";

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return ContenutoGroup::className();
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_backend_icon');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'image-outline';
    }

    /**
     * @inheritdoc
     */
    public function admin(array $params = array())
    {
        if ($this->getVarValue('icon_name')) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">'.Module::t('Nessuna icona selezionata.').'</span></div>';
        }
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

        return ArrayHelper::merge(parent::config(), $configs);
    }
}