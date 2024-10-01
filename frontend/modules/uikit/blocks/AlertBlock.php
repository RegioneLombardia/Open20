<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\InformativoGroup;
use yii\helpers\ArrayHelper;


/**
 * Alert Block.
 *
 */
final class AlertBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    protected $component = "alert";

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return InformativoGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_alert');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'alert-outline';
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        if($this->getVarValue('title') || $this->getVarValue('content') || $this->getVarValue('additional_content')) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Yii::t('backendobjects', 'block_module_alert_no_content') . '</span></div>';
        }
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
