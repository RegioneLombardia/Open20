<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\ContenitoreGroup;
use yii\helpers\ArrayHelper;

final class Open2LayoutTwoColumnsBlock extends BaseUikitBlock {

    public $isContainer = true;
    public $cacheEnabled = false;

    /**
     * @inheritdoc
     */
    public function disable() {
        return 1;
    }

    public function name() {
        return Yii::t('backendobjects', 'block_module_backend_two-columns-layout-open20');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'view_column';
    }

    /**
     * @inheritdoc
     */
    public function config() {

        $configs = [
            'vars' => [
                ['var' => 'width',
                    'label' => Yii::t('backendobjects', 'block_layout_width_label'),
                    'initvalue' => 6,
                    'type' => 'zaa-select', 'options' => [
                        ['value' => 1, 'label' => '1'],
                        ['value' => 2, 'label' => '2'],
                        ['value' => 3, 'label' => '3'],
                        ['value' => 4, 'label' => '4'],
                        ['value' => 5, 'label' => '5'],
                        ['value' => 6, 'label' => '6'],
                        ['value' => 7, 'label' => '7'],
                        ['value' => 8, 'label' => '8'],
                        ['value' => 9, 'label' => '9'],
                        ['value' => 10, 'label' => '10'],
                        ['value' => 11, 'label' => '11'],
                    ],
                ], ['var' => 'breakpoint',
                    'label' => 'Breakpoint',
                    'initvalue' => 'md',
                    'type' => 'zaa-select', 'options' => [
                        ['value' => 'xs', 'label' => 'xs'],
                        ['value' => 'sm', 'label' => 'sm'],
                        ['value' => 'md', 'label' => 'md'],
                        ['value' => 'lg', 'label' => 'lg'],
                        ['value' => 'xl', 'label' => 'xl'],
                    ],
                ],
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
            ],
            'cfgs' => [
                ['var' => 'leftColumnClasses', 'label' => Yii::t('backendobjects', 'block_layout_left_column_css_class'), 'type' => 'zaa-text'],
                ['var' => 'rightColumnClasses', 'label' => Yii::t('backendobjects', 'block_layout_right_column_css_class'), 'type' => 'zaa-text'],
                ['var' => 'rowDivClass', 'label' => Yii::t('backendobjects', 'block_layout_row_column_css_class'), 'type' => 'zaa-text'],
            ],
            'placeholders' => [
                [
                    ['var' => 'left', 'cols' => $this->getExtraValue('leftWidth'), 'label' => Yii::t('backendobjects', 'block_layout_placeholders_left')],
                    ['var' => 'right', 'cols' => $this->getExtraValue('rightWidth'), 'label' => Yii::t('backendobjects', 'block_layout_placeholders_right')],
                ]
            ],
        ];

        $this->cacheEnabled = $this->getVarValue('cache');

        return $configs;
    }

    /**
     * @inheritdoc
     */
    public function extraVars() {
        return [
            'leftWidth' => $this->getVarValue('width', 6),
            'rightWidth' => 12 - $this->getVarValue('width', 6),
        ];
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        return $this->frontend();
    }

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return ContenitoreGroup::class;
    }

}
