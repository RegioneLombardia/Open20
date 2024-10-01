<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;


final class LayoutSectionNewBlock extends \app\modules\uikit\BaseUikitBlock
{
    public $isContainer = true;
    public $cacheEnabled = false;
    /**
     * @inheritdoc
     */
    
    protected $component = "layoutsectionnew";

    public function config(){
        $configs = parent::config();
        $this->cacheEnabled = $this->getVarValue('cache');
        
        return $configs;
    }

    public function disable(){
        return 0;
    }
    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_backend_layoutsectionnew');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'aspect_ratio';
    }

    /**
     * @inheritdoc
     */
    protected function getPlaceholders()
    {
        return [
            ['var' => 'content', 'cols' => $this->getExtraValue('content')]
        ];
    }

    public function extraVars()
    {
        $this->extraValues['content'] = 12;
        return parent::extraVars();
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return LegacyGroup::class;
    }
}