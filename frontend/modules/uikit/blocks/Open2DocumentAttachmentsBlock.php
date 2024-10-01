<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\Module;
use app\modules\backendobjects\frontend\blockgroups\ElementiBaseGroup;
use trk\uikit\Uikit;
use app\modules\uikit\BaseUikitBlock;
use yii\helpers\ArrayHelper;

class Open2DocumentAttachmentsBlock extends BaseUikitBlock {

    /**
     * @inheritdoc
     */
    protected $component = "open2documentattachments";

    public function disable() {
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return ElementiBaseGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_backend_documentattachments');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'attach_file';
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        if (count($this->getVarValue('items', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }

    public function frontend(array $params = array()) {
        $blockId = $this->getEnvOption('id');
        $params['blockItemId'] = $blockId;
        if (!Uikit::element('data', $params, '')) {
            $configs = $this->getValues();
            $configs["id"] = Uikit::unique($this->component);
            $params['data'] = Uikit::configs($configs);
            $params['debug'] = $this->config();
            $params['filters'] = $this->filters;
        }
        return $this->view->render($this->getViewFileName('php'), $params, $this);
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
