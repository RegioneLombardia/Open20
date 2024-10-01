<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use trk\uikit\Uikit;

class SiteMapBlock extends BaseUikitBlock
{

    public $cacheEnabled = true;

    public function init()
    {
        parent::init();
        
    }
    /**
     * @inheritdoc
     */
    protected $component = "sitemap";

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return LegacyGroup::className();
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Module::t('sitemap');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'data_usage';
    }

    /**
     * @inheritdoc
     */
    public function admin(array $params = array())
    {
        return '<div><span class="block__empty-text">'.Module::t('no_content').'</span></div>';
    }

    public function frontend(array $params = array())
    {
       if (!Uikit::element('data', $params, '')) {
            $configs        = $this->getValues();
            $configs["id"]  = Uikit::unique($this->component);
            $params['data'] = Uikit::configs($configs);
        }
        return $this->view->render($this->getViewFileName('php'), $params, $this);
    }

    
}