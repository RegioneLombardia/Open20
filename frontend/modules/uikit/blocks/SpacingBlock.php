<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use luya\cms\helpers\BlockHelper;
use app\modules\backendobjects\frontend\blockgroups\SeparatoriGroup;
use yii\helpers\ArrayHelper;

/**
 * Margin Top/Bottom block with Paragraph.
 *
 * @since 1.0.0
 */
final class SpacingBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    public $cacheEnabled = true;
    
    protected function getSpacings()
    {
        return [
            1 => Yii::t('backendobjects', 'block_spacing_small_space'),
            2 => Yii::t('backendobjects', 'block_spacing_medium_space'),
            3 => Yii::t('backendobjects', 'block_spacing_large_space'),
        ];
    }
    
    public function blockGroup()
    {
        return SeparatoriGroup::class;
    }
    
    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_block_spacing_name');
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
    public function icon()
    {
        return 'format-line-spacing';
    }

    /**
     * @inheritdoc
     */
    public function config()
    {
        return [
            'vars' => [
                [
                    'var' => 'spacing',
                    'label' => Yii::t('backendobjects', 'block_spacing_spacing_label'),
                    'description' => 'Seleziona la spaziatura desiderata. Di default lo spazio sarà piccolo. Le spaziature sono circa di 30px, 60px e 80px.',
                    'initvalue' => 1,
                    'type' => self::TYPE_SELECT,
                    'options' => BlockHelper::selectArrayOption($this->getSpacings()),
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function extraVars()
    {
        return [
            'spacingLabel' => $this->getSpacings()[$this->getVarValue('spacing', 1)],
        ];
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        $spaceLabel='';
        switch($this->getVarValue('spacing')){
         
            case 2:
                $spaceLabel='Spazio medio.';
                break;
            case 3:
                $spaceLabel='Spazio grande.';
                break;
            default:
                $spaceLabel='Spazio piccolo.';
                break;
        }

        $typeOfSpace=$this->frontend();
 
        $typeOfSpace = str_replace('<p class="spacing-block">', '<p class="spacing-block">'.$spaceLabel, $typeOfSpace);
        return $typeOfSpace;
        /*return $this->frontend();
        return '<span class="block__empty-text">{{ extras.spacingLabel }}</span>';*/
    }
}
