<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use trk\uikit\Uikit;

class IconBlock extends BaseUikitBlock
{
    public $cacheEnabled = true;

    /**
     * @inheritdoc
     */
    protected $component = "icon";

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
        return Module::t('icon');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'image-outline';
    }

    /**
     * @inheritdoc
     */
    public function admin(array $params = array())
    {
        return $this->frontend($params);
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