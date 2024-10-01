<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use luya\cms\frontend\Module;
use app\modules\backendobjects\frontend\blockgroups\SviluppoGroup;
use luya\cms\base\PhpBlock;
use yii\helpers\ArrayHelper;


/**
 * HTML Block
 *
 * @since 1.0.0
 */
final class HtmlBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    public $cacheEnabled = true;

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return SviluppoGroup::class;
    }

    public function disable(){
        return 0;
    }
    
    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_backend_html');
    }
    
    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'code-tags';
    }

    /**
     * @inheritdoc
     */
    public function config()
    {
        return [
            'vars' => [
                ['var' => 'html', 'label' => Yii::t('backendobjects', 'block_html_html_label'), 'type' => self::TYPE_TEXTAREA],
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
                ['var' => 'raw', 'label' => Yii::t('backendobjects', 'block_html_cfg_raw_label'), 'type' => self::TYPE_CHECKBOX]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        $message =  Module::t('block_html_no_content');
        return <<<EOT
    	{% if vars.html is empty %}
    		<span class="block__empty-text">{$message}</span>{% else %}
    		{% if cfgs.raw == 1 %}
    			{{ vars.html | raw }}
    		{% else %}
                <code>{{ vars.html | escape }}</code>
    		{% endif %}
    	{% endif %}
EOT;
    }
}
