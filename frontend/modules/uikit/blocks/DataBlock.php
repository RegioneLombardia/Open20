<?php

namespace app\modules\uikit\blocks;

use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use luya\cms\frontend\blockgroups\DevelopmentGroup;
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
        return DevelopmentGroup::className();
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