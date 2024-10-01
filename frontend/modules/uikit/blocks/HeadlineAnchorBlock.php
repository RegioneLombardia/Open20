<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use trk\uikit\Module;


final class HeadlineAnchorBlock  extends \app\modules\uikit\BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    protected $component = "headlineanchor";


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
        return Module::t('headline-anchor');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'format-size';
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        if($this->getVarValue('content')) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }
}
