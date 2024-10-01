<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use luya\TagParser;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Paragraph Text Block.
 *
 * @since 1.0.0
 */
final class FooterTextBlock extends BaseUikitBlock {

    /**
     * @inheritdoc
     */
    public $cacheEnabled = true;

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return LegacyGroup::class;
    }

    public function disable(){
        return 0;
    }
    /**
     * @inheritdoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_text_name');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'format-align-left';
    }

    /**
     * @inheritdoc
     */
    public function config() {
        return [
            'vars' => [
                ['var' => 'content', 'label' => Yii::t('backendobjects', 'block_text_content_label'), 'type' => 'zaa-wysiwyg'],
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
                ['var' => 'cssClass', 'label' => Yii::t('backendobjects', 'block_cfg_additonal_css_class'), 'type' => 'zaa-text'],
            ]
        ];
    }

    /**
     * Get the text based on type input.
     */
    public function getText() {
        $text = $this->getVarValue('content');

        if ($this->getVarValue('textType', 0) == 1) {
            return TagParser::convertWithMarkdown($text);
        }

        return nl2br($text);
    }

    /**
     * @inheritdoc
     */
    public function extraVars() {
        return [
            'text' => $this->getText(),
        ];
    }

    public function admin() {
        return '<p>{% if vars.content is empty %}<span class="block__empty-text">' . Yii::t('backendobjects', 'block_text_no_content') . '</span>' .
                '{% elseif vars.content is not empty and vars.textType == 1 %}{{ extras.text }}{% elseif vars.content is not empty %}{{ extras.text }}{% endif %}</p>';
    }

}
