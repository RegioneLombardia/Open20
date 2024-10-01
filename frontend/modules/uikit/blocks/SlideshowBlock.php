<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use trk\uikit\Module;

final class SlideshowBlock extends \app\modules\uikit\BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    public $component = "slideshow";

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return LegacyGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Module::t('new-slideshow');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'camera-burst';
    }

    /**
     * @inheritdoc
     */
    public function frontend(array $params = array())
    {
        if(count($this->getVarValue('items', []))) {
            return parent::frontend($params);
        } else {
            return "";
        }
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        if($output = $this->frontend()) {
            return $output;
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }
}
