<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use Yii;
use yii\helpers\ArrayHelper;

final class FooterImageBlock extends BaseUikitBlock {

    /**
     * @inheritdoc
     */
    public $component = "image";

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
        return Yii::t('backendobjects', 'block_module_backend_image');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'image-outline';
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        if ($this->getExtraValue('image')) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Yii::t('backendobjects', 'block_module_backend_image_no_content') . '</span></div>';
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
