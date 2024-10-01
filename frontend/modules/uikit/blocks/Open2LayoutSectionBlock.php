<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\ContenitoreGroup;
use yii\helpers\ArrayHelper;

final class Open2LayoutSectionBlock extends BaseUikitBlock {

    public $isContainer = true;
    public $cacheEnabled = false;

    /**
     * @inheritdoc
     */
    public function disable() {
        return 1;
    }

    protected $component = "open2layoutsection";

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
        $config = ArrayHelper::merge(parent::config(), $configs);
        $this->cacheEnabled = $this->getVarValue('cache');

        return $config;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_backend_layoutsection');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'aspect_ratio';
    }

    /**
     * @inheritdoc
     */
    protected function getPlaceholders() {
        return [
            ['var' => 'content', 'cols' => $this->getExtraValue('content')]
        ];
    }

    public function extraVars() {
        $this->extraValues['content'] = 12;
        return parent::extraVars();
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return ContenitoreGroup::class;
    }

}
