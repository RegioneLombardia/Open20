<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\ContenutoGroup;
use app\modules\uikit\BaseUikitBlock;
use Yii;
use yii\helpers\ArrayHelper;


final class HeroBannerBlock extends BaseUikitBlock {

    public $cacheEnabled = false;

    /**
     * @inheritdoc
     */
    protected $component = "herobanner";

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return ContenutoGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_herobanner');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'view-compact-outline';
    }

    /**
     * @inheritdoc
     */
    public function admin(array $params = array()) {
        return $this->frontend();
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
