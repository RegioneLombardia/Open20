<?php
namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use Yii;
use luya\cms\models\NavItemPage;
use trk\uikit\Uikit;



class LastPageUpdateBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    protected $component = "lastpageupdate";

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
        return Module::t('last-page-update');
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
    public function admin() {
        
        return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        
    }

    public function frontend(array $params = array()) 
    {
        $date_update = null;
        $time_update = null;
       
        $blockId = $this->getEnvOption('id');
        $params['blockItemId'] = $blockId;
        
        $configs = $this->getValues();
        $configs["id"] = Uikit::unique($this->component);
        $params['data'] = Uikit::configs($configs);
        $params['debug'] = $this->config();
        $params['filters'] = $this->filters;
        
        $nav = $this->getEnvOption('pageObject')->one();
        if (!is_null($nav)) 
        {
            $page = NavItemPage::findOne(['nav_item_id' => $nav->id]);
            if (!is_null($page)) 
            {
                $date_update = Yii::$app->formatter->asDate($nav->timestamp_update);
                $time_update = Yii::$app->formatter->asTime($nav->timestamp_update);
            }
        }
        $params['date_update'] = $date_update;
        $params['time_update'] = $time_update;
        return $this->view->render($this->getViewFileName('php'), $params, $this);
    }
}
