<?php

namespace app\modules\uikit\blocks;

use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use luya\cms\frontend\blockgroups\MediaGroup;

final class GalleryPanelBlock extends BaseUikitBlock
{
    protected $component = "gallerypanel";

    //put your code here
    public function admin()
    {
        if (count($this->getVarValue('items', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">'.Module::t('no_content').'</span></div>';
        }
    }

    public function name()
    {
        return Module::t('gallerypanel');
    }

    /**
     * @inheritDoc
     */
    public function blockGroup()
    {
        return MediaGroup::class;
    }

    public function icon()
    {
        return 'view_module';
    }
}