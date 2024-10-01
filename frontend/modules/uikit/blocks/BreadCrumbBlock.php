<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use Yii;


class BreadCrumbBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    protected $component = "breadcrumb";

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
        return Module::t('breadcrumb');
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
        
        return "Breadcrumb of page.";
    }

    /**
     * 
     * @param array $params
     * @return string
     */
    public function frontend(array $params = array())
    {
        
        $params['items'] = Yii::$app->menu->current->with(['hidden'])->teardown;
        return $this->view->render($this->getViewFileName('php'), $params, $this);
    }
}
