<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;

final class ModalPanelBlock extends BaseUikitBlock {

    /**
     * @inheritdoc
     */
    protected $component = "modalpanel";

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return LegacyGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Module::t('modalpanel');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'line_weight';
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        if (strlen($this->getVarValue('text'))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }

}
