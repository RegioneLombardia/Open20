<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use trk\uikit\Uikit;

class DataBlock extends BaseUikitBlock
{
    public $cacheEnabled = false;

    /**
     * @inheritdoc
     */
    protected $component = "data";

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return LegacyGroup::className();
    }

    public function disable(){
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Module::t('data');
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
        if (!Uikit::element('data', $params, '')) {
            $configs        = $this->getValues();
            $configs["id"]  = Uikit::unique($this->component);
            $params['data'] = Uikit::configs($configs);
        }
        return $this->view->render($this->getViewFileName('php'), $params, $this);
    }

    public function frontend(array $params = array())
    {
        return "";
    }
}