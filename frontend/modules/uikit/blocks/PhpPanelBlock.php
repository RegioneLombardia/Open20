<?php



namespace app\modules\uikit\blocks;


use app\modules\uikit\Module;
use luya\cms\frontend\blockgroups\TextGroup;

class PhpPanelBlock extends \app\modules\uikit\BaseUikitBlock
{
    
    public $cacheEnabled = false;
    /**
     * @inheritdoc
     */
    protected $component = "phppanel";

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return TextGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Module::t('phppanel');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'line_weight';
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        if(count($this->getVarValue('items', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }
}