<?php

namespace app\modules\uikit\blocks;


use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\Module;


final class TabsPanelBlock extends \app\modules\uikit\BaseUikitBlock
{
    
    
    protected $component = "tabspanel";
    
    
    //put your code here
    public function admin() 
    {
        if(count($this->getVarValue('items', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }

    public function name() {
        return Module::t('tabspanel');
    }

   /**
     * @inheritDoc
     */
    public function blockGroup()
    {
        return LegacyGroup::class;
    }

    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'format_color_text';
    }
}
