<?php
namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;

final class LinkPanelBlock extends BaseUikitBlock {

    /**
     *
     * @inheritdoc
     */
    protected $component = "linkpanel";

    /**
     *
     * @inheritdoc
     */
    public function blockGroup() {
        return LegacyGroup::class;
    }

    /**
     *
     * @inheritdoc
     */
    public function name() {
        return Module::t('linkpanel');
    }

    /**
     *
     * @inheritdoc
     */
    public function icon()
    {
        return 'line_weight';
    }

    /**
     *
     * @inheritdoc
     */
    public function admin() {
        if (strlen($this->getVarValue('title'))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }
}
