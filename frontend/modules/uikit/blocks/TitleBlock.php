<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\ContenutoGroup;
use yii\helpers\ArrayHelper;


/**
 * Heading-Title Block.
 *
 * @since 1.0.0
 */
final class TitleBlock extends BaseUikitBlock
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
        return Yii::t('backendobjects', 'block_module_title_name');
    }
    
    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return ContenutoGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'format-size';
    }

    /**
     * @inheritdoc
     */
    public function config()
    {
        return [
            'vars' => [
                [
                    'var' => 'content', 
                    'label' => Yii::t('backendobjects', 'block_title_content_label'), 
                    'description'=> 'Inserisci il testo del titolo.',
                    'type' => self::TYPE_TEXT
                ],
                [
                    'var' => 'headingType', 
                    'label' => Yii::t('backendobjects', 'block_title_headingtype_label'), 
                    'description'=> 'Scegli la dimensione del titolo. 1 è la grandezza massima, 6 è a minima',
                    'type' => self::TYPE_SELECT, 'initvalue' => 'h1', 'options' => [
                        ['value' => 'h1', 'label' => Yii::t('backendobjects', 'block_title_headingtype_heading') . ' 1'],
                        ['value' => 'h2', 'label' => Yii::t('backendobjects', 'block_title_headingtype_heading') . ' 2'],
                        ['value' => 'h3', 'label' => Yii::t('backendobjects', 'block_title_headingtype_heading') . ' 3'],
                        ['value' => 'h4', 'label' => Yii::t('backendobjects', 'block_title_headingtype_heading') . ' 4'],
                        ['value' => 'h5', 'label' => Yii::t('backendobjects', 'block_title_headingtype_heading') . ' 5'],
                        ['value' => 'h6', 'label' => Yii::t('backendobjects', 'block_title_headingtype_heading') . ' 6'],
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
            ],
            'cfgs' => [
                [
                    'var' => 'cssClass', 
                    'label' => Yii::t('backendobjects', 'block_cfg_additonal_css_class'), 
                    'description'=> 'Classe css associata al componente.',
                    'type' => self::TYPE_TEXT],
            ]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function admin()
    {
        return '{% if vars.content is not empty %}<{{vars.headingType}}>{{ vars.content }}</{{vars.headingType}}>{% else %}<span class="block__empty-text">' . Yii::t('backendobjects', 'block_title_no_content') . '</span>{% endif %}';
    }
}
