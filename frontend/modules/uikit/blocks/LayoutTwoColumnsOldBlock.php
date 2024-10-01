<?php
namespace app\modules\uikit\blocks;

use trk\uikit\BaseLayoutBlock;
use trk\uikit\Module;
use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;


final class LayoutTwoColumnsOldBlock extends BaseLayoutBlock
{

    public function init()
    {
        parent::init();
        $this->cacheEnabled = false;
    }

    /**
     * @return array
     */
    public function availableLayouts() {
        return ['halves', 'thirds-2-1', 'thirds-1-2', 'quarters-3-1', 'quarters-1-3', 'fixed-left', 'fixed-right'];
    }

    /**
     * @return string
     */
    public function defaultLayout() {
        return 'halves';
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Module::t('two-columns-layout-open20');
    }

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return LegacyGroup::class;
    }
}