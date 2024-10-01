<?php

namespace app\modules\uikit\blocks;


use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\Module;
use trk\uikit\Uikit;

class DocumentAttachmentsBlock extends \app\modules\uikit\BaseUikitBlock
{
   /**
     * @inheritdoc
     */
    protected $component = "documentattachments";

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
        return Module::t('documentattachments');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'attach_file';
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
    
    
    public function frontend(array $params = array()) 
    {
        $blockId = $this->getEnvOption('id');
        $params['blockItemId'] = $blockId;
        if(!Uikit::element('data', $params, '')) {
            $configs = $this->getValues();
            $configs["id"] =  Uikit::unique($this->component);
            $params['data'] = Uikit::configs($configs);
            $params['debug'] = $this->config();
            $params['filters'] = $this->filters;
        }
        return $this->view->render($this->getViewFileName('php'), $params, $this);
        
    }
}
