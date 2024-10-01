<?php



namespace app\modules\uikit\blocks;


use app\modules\uikit\Module;
use luya\cms\frontend\blockgroups\TextGroup;
use app\modules\backendobjects\frontend\blockgroups\SviluppoGroup;

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
        return SviluppoGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Module::t('Codice PHP');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'code-tags';
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        if(count($this->getVarValue('items', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('Codice PHP') . '</span></div>';
        }
    }
}