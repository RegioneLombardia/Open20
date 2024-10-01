<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\SviluppoGroup;
use trk\uikit\Uikit;
use yii\helpers\ArrayHelper;

class Open2DataBlock extends BaseUikitBlock {

    public $cacheEnabled = false;

    public function disable() {
        return 1;
    }

    /**
     * @inheritdoc
     */
    protected $component = "open2data";

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return SviluppoGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_backend_data');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'data_usage';
    }

    /**
     * @inheritdoc
     */
    public function admin(array $params = array()) {
        if (!Uikit::element('data', $params, '')) {
            $configs = $this->getValues();
            $configs["id"] = Uikit::unique($this->component);
            $params['data'] = Uikit::configs($configs);
        }
        return $this->view->render($this->getViewFileName('php'), $params, $this);
    }

    public function frontend(array $params = array()) {
        return "";
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
