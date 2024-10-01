<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\SeparatoriGroup;
use yii\helpers\ArrayHelper;

/**
 * Simple horizontal line block
 *
 * @since 1.0.0
 */
final class LineBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    public $cacheEnabled = true;
    
    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_line_name');
    }
    
    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return SeparatoriGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'minus';
    }
    
    /**
     * @inheritdoc
     */
    public function getIsDirtyDialogEnabled()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function config()
    {
        return [
           'vars' => [
                [
                    'var' => 'lineSpace', 
                    'label' => Yii::t('backendobjects', 'block_line_linespace_label'), 
                    'type' => 'zaa-select', 
                    'description'=> Yii::t('backendobjects', 'Se selezionata permette di decidere la spaziatura in alto e in basso della linea. Se seleziono per esempio 4px significa che la linea avrà 4px di spaziatura sopra e sotto.'),
                    'options' => [
                        ['value' => 'my-1', 'label' => '4px ' . Yii::t('backendobjects', 'block_line_linespace_space')],
                        ['value' => 'my-2', 'label' => '8px ' . Yii::t('backendobjects', 'block_line_linespace_space')],
                        ['value' => 'my-3', 'label' => '16px ' . Yii::t('backendobjects', 'block_line_linespace_space')],
                        ['value' => 'my-4', 'label' => '24px ' . Yii::t('backendobjects', 'block_line_linespace_space')],
                        ['value' => 'my-5', 'label' => '48px ' . Yii::t('backendobjects', 'block_line_linespace_space')],
                    ], 'initvalue' => 'my-1'],
                [
                    'var' => 'lineStyle', 
                    'label' => Yii::t('backendobjects', 'Stile della linea'), 
                    'type' => 'zaa-select', 
                    'description'=> Yii::t('backendobjects', 'Se selezionata permette di decidere lo stile della linea (che di default è una linea piena e continua).'),
                    'options' => [
                        ['value' => 'dotted', 'label' => Yii::t('backendobjects', 'block_line_linestyle_dotted')],
                        ['value' => 'dashed', 'label' => Yii::t('backendobjects', 'block_line_linestyle_dashed')],
                        ['value' => 'solid', 'label' => Yii::t('backendobjects', 'block_line_linestyle_solid')],
                    ], 'initvalue' => 'solid'],
                [
                    'var' => 'lineWidth', 
                    'label' => Yii::t('backendobjects', 'Spessore della linea'), 
                    'description'=> Yii::t('backendobjects', 'Se selezionata permette di decidere lo spessore della linea (che di default è a 1px).'),
                    'type' => 'zaa-select', 
                    'options' => [
                        ['value' => '1px', 'label' => '1px'],
                        ['value' => '2px', 'label' => '2px'],
                        ['value' => '3px', 'label' => '3px'],
                    ], 'initvalue' => '1px'],
                [
                    'var' => 'lineColor', 
                    'label' => Yii::t('backendobjects', 'block_line_linecolor_label'), 
                    'description'=> Yii::t('backendobjects', 'Se selezionata permette di decidere il colore della linea (che di default è a grigio).'),
                    'type' => 'zaa-select', 
                    'options' => [
                        ['value' => 'border-200', 'label' => Yii::t('backendobjects', 'block_line_linecolor_grey')],
                        ['value' => 'black-border-color', 'label' => Yii::t('backendobjects', 'block_line_linecolor_black')],
                        ['value' => 'white-border-color', 'label' => Yii::t('backendobjects', 'block_line_linecolor_white')],
                        ['value' => 'primary-border-color', 'label' => Yii::t('backendobjects', 'Colore primario')],
                        ['value' => 'secondary-border-color', 'label' => Yii::t('backendobjects', 'Colore secondario')],
                    ], 'initvalue' => 'border-200'],
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
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        return '<p><hr class="cms-bk-hr"/></p>';
    }
}
