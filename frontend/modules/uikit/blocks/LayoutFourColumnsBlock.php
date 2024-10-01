<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitLayoutBlock;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use yii\helpers\ArrayHelper;


/**
 * Three Columns Layout Block : quarters
 *
 */
final class LayoutFourColumnsBlock extends BaseUikitLayoutBlock {

    public $isContainer = true;
    public $cacheEnabled = false;

    /**
     * @return array
     */
    public function availableLayouts() {
        return ['quarters'];
    }

    /**
     * @return string
     */
    public function defaultLayout() {
        return 'quarters';
    }

    public function disable(){
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_four-columns-layout');
    }

    public function config() {
        $configs = parent::config();

        $vars = [
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
            ['var' => 'cache',
                'label' => 'Enable cache',
                'initvalue' => '',
                'type' => 'zaa-checkbox',
            ],
            ['var' => 'class',
                'label' => 'Class',
                'initvalue' => '',
                'type' => 'zaa-text',
            ]
        ];

        array_push($configs['vars'], ...$vars);

        $this->cacheEnabled = $this->getVarValue('cache');

        return $configs;
    }

    /**
     * @inheritdoc
     */

    public function blockGroup()
    {
        return LegacyGroup::class;

    }

    /**
     * @inheritdoc
     */
    public function admin() {
        return $this->frontend();
    }

}
