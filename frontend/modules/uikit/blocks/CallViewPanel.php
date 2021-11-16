<?php
namespace app\modules\uikit\blocks;

use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use luya\cms\frontend\blockgroups\TextGroup;
use trk\uikit\Uikit;

/**
 * Description of CallViewPanel
 *
 */
final class CallViewPanel extends BaseUikitBlock {
    
    public $cacheEnabled = false;
    
   /**
     * @inheritdoc
     */
    protected $component = "callviewpanel";

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return TextGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Module::t('callviewpanel');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'line_weight';
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        if (strlen($this->getVarValue('text'))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }
    
    /**
     * @param array $params
     * @return mixed
     */
    public function frontend(array $params = array())
    {
        if(!Uikit::element('data', $params, '')) {
            $configs = $this->getValues();
            $configs["id"] =  Uikit::unique($this->component);
            $params['data'] = Uikit::configs($configs);
            $params['debug'] = $this->config();
            $params['filters'] = $this->filters;
        }
        return $this->view->render($this->getVarValue('viewName'), $params, $this);
    }

	/**
	 * @inheritdoc
	 */
    public function getViewPath()
    {
        return  $this->getVarValue('viewPath');
    }
}
