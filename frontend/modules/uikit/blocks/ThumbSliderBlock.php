<?php

namespace app\modules\uikit\blocks;

use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use app\modules\backendobjects\frontend\blockgroups\ContenutoGroup;
use yii\helpers\ArrayHelper;

final class ThumbSliderBlock extends BaseUikitBlock {

    protected $component = "thumbslider";

    //put your code here
    public function admin() {
        return '<div><span class="block__empty-text"><img src="/img/preview_cms/thumbslider-preview.png"></span></div>';
    }

    public function name() {
        return Module::t('Thumbslider');
    }

    /**
     * @inheritDoc
     */
    public function blockGroup() {
        return ContenutoGroup::class;
    }

    public function icon() {
        return 'view-gallery-outline';
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
