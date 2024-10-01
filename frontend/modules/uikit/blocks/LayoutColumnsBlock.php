<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\backendobjects\frontend\blockgroups\LayoutInPaginaGroup;
use app\modules\uikit\BaseUikitBlock;


final class LayoutColumnsBlock extends BaseUikitBlock {

    public $isContainer = true;
    public $cacheEnabled = false;

    /**
     * @inheritdoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_backend-columns-layout');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'view-column';
    }

    /**
     * @inheritdoc
     */
    public function config() {

        $n_cols = $this->getVarValue('n_colonne', 1);
        $col_options = $this->getVarValue('breakpoints', 'xs');

        $configs = [
            'vars' => [
                [
                    'var' => 'n_colonne',
                    'label' => Yii::t('backendobjects', 'Scegli il layout della tua sezione'),
                    'initvalue' => '1',
                    'description' => 'Le dimensioni e la formattazione del layout riguardano gli schermi desktop. Per schermi mobile ogni colonna avrà larghezza 100%.',
                    'type' => 'zaa-radio',
                    'options' => [
                        ['value' => 8, 'label' => 'Layout con una colonna (larghezza 100% su desktop e mobile)', 'image' => '/img/layout_columns_template/1col.png'],
                        ['value' => 1, 'label' => 'Layout con due colonne (larghezza 50% su desktop, 100% mobile)', 'image' => '/img/layout_columns_template/2col.png'],
                        ['value' => 2, 'label' => 'Layout con tre colonne (larghezza 33% su desktop, 100% mobile)', 'image' => '/img/layout_columns_template/3col.png'],
                        ['value' => 3, 'label' => 'Layout con quattro colonne (larghezza 25% su desktop, 100% mobile)', 'image' => '/img/layout_columns_template/4col.png'],
                        ['value' => 4, 'label' => 'Layout con cinque colonne (larghezza 20% desktop e 100% mobile)', 'image' => '/img/layout_columns_template/5col.png'],
                        ['value' => 5, 'label' => 'Layout con due colonne (larghezza 67%-33% desktop e 100% mobile)', 'image' => '/img/layout_columns_template/66-33.png'],
                        ['value' => 6, 'label' => 'Layout con due colonne (larghezza 33%-67% desktop e 100% mobile)', 'image' => '/img/layout_columns_template/33-66.png'],
                        ['value' => 7, 'label' => 'Layout con tre colonne (larghezza 20%-60%-20% desktop e 100% mobile)', 'image' => '/img/layout_columns_template/20-60-20.png'],
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
                [
                    'var' => 'cache',
                    'label' => 'Abilita cache',
                    'description' => 'Se selezionato il contenuto della sezione sarà statico e non subirà variazioni nel breve periodo. Consigliato solo per i contenuti statici.',
                    'initvalue' => '',
                    'type' => 'zaa-checkbox',
                ],
            ],
            'cfgs' => [
                [
                    'var' => 'addClass',
                    'label' => 'Impostazioni aggiuntive',
                    'description' => 'Se selezionate aggiungono delle classi alle colonne (previa selezione per numero). ',
                    'initvalue' => '',
                    'type' => 'zaa-multiple-inputs',
                    'options' => [
                        [
                            'var' => 'n_col',
                            'label' => 'Colonna a cui aggiungere la classe',
                            'initvalue' => 1,
                            'type' => 'zaa-select',
                            'options' => [
                                ['value' => 1, 'label' => 'Prima colonna'],
                                ['value' => 2, 'label' => 'Seconda colonna'],
                                ['value' => 3, 'label' => 'Terza colonna'],
                                ['value' => 4, 'label' => 'Quarta colonna'],
                                ['value' => 5, 'label' => 'Quinta colonna'],
                            ],
                        ],
                        [
                            'var' => 'css',
                            'label' => 'Classe CSS',
                            'initValue' => '',
                            'placeholder' => '',
                            'type' => 'zaa-text',
                        ],
                        [
                            'var' => 'border',
                            'label' => 'Bordo da aggiungere alla colonna',
                            'initvalue' => '',
                            'type' => 'zaa-select',
                            'options' => [
                                ['value' => '', 'label' => 'Nessun bordo'],
                                ['value' => 'border border-100', 'label' => 'Bordo grigio chiaro'],
                                ['value' => 'border border-600', 'label' => 'Bordo grigio scuro'],
                                ['value' => 'border border-black', 'label' => 'Bordo nero'],
                                ['value' => 'border border-primary', 'label' => 'Bordo primario'],
                                ['value' => 'border border-secondary', 'label' => 'Bordo secondario'],
                                ['value' => 'border border-tertiary', 'label' => 'Bordo terziario']
                            ],
                        ]
                    ],
                ],
                [
                    'var' => 'add_affix',
                    'label' => 'Seleziona la colonna da fissare',
                    'description' => 'Se selezionata imposta la colonna da fissare in alto',
                    'initvalue' => '',
                    'type' => 'zaa-select', 
                    'options' => [
                        ['value' => 'affix-column-1', 'label' => 'Prima colonna'],
                        ['value' => 'affix-column-2', 'label' => 'Seconda colonna'],
                        ['value' => 'affix-column-3', 'label' => 'Terza colonna'],
                        ['value' => 'affix-column-4', 'label' => 'Quarta colonna'],
                        ['value' => 'affix-column-5', 'label' => 'Quinta colonna'],
                    ],
                ],
                
                [
                    'var' => 'rowDivClass',
                    'label' => 'Classe css',
                    'description' => 'Classe css associata al contenitore delle colonne.',
                    'type' => 'zaa-text',
                ],
            ],
        ];

        $cols_placeholders = [];

        switch ($n_cols) {
            case 8:
                $cols_placeholders[0][] = ['var' => "col1", 'cols' => '12 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 1')];
                break;
            case 1:
                $cols_placeholders[0][] = ['var' => "col1", 'cols' => '6 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 1')];
                $cols_placeholders[0][] = ['var' => "col2", 'cols' => '6 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 2')];
                break;
            case 2:
                $cols_placeholders[0][] = ['var' => "col1", 'cols' => '4 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 1')];
                $cols_placeholders[0][] = ['var' => "col2", 'cols' => '4 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 2')];
                $cols_placeholders[0][] = ['var' => "col3", 'cols' => '4 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 3')];
                break;
            case 3:
                $cols_placeholders[0][] = ['var' => "col1", 'cols' => '3 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 1')];
                $cols_placeholders[0][] = ['var' => "col2", 'cols' => '3 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 2')];
                $cols_placeholders[0][] = ['var' => "col3", 'cols' => '3 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 3')];
                $cols_placeholders[0][] = ['var' => "col4", 'cols' => '3 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 4')];
                break;

            case 4:
                $cols_placeholders[0][] = ['var' => "col1", 'cols' => ' col layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 1')];
                $cols_placeholders[0][] = ['var' => "col2", 'cols' => ' col layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 2')];
                $cols_placeholders[0][] = ['var' => "col3", 'cols' => ' col layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 3')];
                $cols_placeholders[0][] = ['var' => "col4", 'cols' => ' col layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 4')];
                $cols_placeholders[0][] = ['var' => "col5", 'cols' => ' col layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 5')];
                break;

            case 5:
                $cols_placeholders[0][] = ['var' => "col1", 'cols' => '8 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 1')];
                $cols_placeholders[0][] = ['var' => "col2", 'cols' => '4 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 2')];
                break;

            case 6:
                $cols_placeholders[0][] = ['var' => "col1", 'cols' => '4 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 1')];
                $cols_placeholders[0][] = ['var' => "col2", 'cols' => '8 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 2')];
                break;

            case 7:
                $cols_placeholders[0][] = ['var' => "col1", 'cols' => '2 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 1')];
                $cols_placeholders[0][] = ['var' => "col2", 'cols' => '8 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 2')];
                $cols_placeholders[0][] = ['var' => "col3", 'cols' => '2 layout-columns', 'label' => Yii::t('backendobjects', 'Colonna 3')];
                break;
        }



        $configs['placeholders'] = $cols_placeholders;

        $this->cacheEnabled = $this->getVarValue('cache');

        return $configs;
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
        return LayoutInPaginaGroup::class;
    }

}
