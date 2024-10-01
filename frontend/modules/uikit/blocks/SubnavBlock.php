<?php
namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;


final class SubnavBlock extends BaseUikitBlock
{

    /**
     *
     * @inheritdoc
     */
    public $component = "subnav";

    /**
     *
     * @inheritdoc
     */
    public function blockGroup()
    {
        return LegacyGroup::class;
    }

    /**
     *
     * @inheritdoc
     */
    public function name()
    {
        return Module::t('subnav-advanced');
    }

    /**
     *
     * @inheritdoc
     */
    public function icon()
    {
        return 'more_horiz';
    }

    /**
     *
     * @inheritdoc
     */
    public function frontend(array $params = array())
    {
        if (count($this->getVarValue('items', []))) {
            return parent::frontend($params);
        } else {
            return "";
        }
    }

    /**
     *
     * @inheritdoc
     */
    public function admin()
    {
        if ($output = $this->frontend()) {
            return $output;
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }
}
