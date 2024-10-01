<?php

namespace app\modules\uikit\blocks;

use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use app\modules\uikit\Module;
use DOMDocument;
use trk\uikit\Uikit;
use yii\helpers\Url;

class IndexHeadlineAnchorBlock extends \app\modules\uikit\BaseUikitBlock 
{

    private $blocks = ['\app\modules\uikit\blocks\HeadlineAnchorBlock'];
    
    /**
     * @inheritdoc
     */
    protected $component = "indexheadlineanchor";

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return LegacyGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Module::t('index-headline-anchor');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'attach_file';
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        if (count($this->getVarValue('items', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }

    public function frontend(array $params = array()) 
    {
        $titles = [];
       
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
            $page = \luya\cms\models\NavItemPage::findOne(['nav_item_id' => $nav->id]);
            if (!is_null($page)) 
            {
                $models = \luya\cms\models\NavItemPageBlockItem::find()->
		andWhere(['nav_item_page_id' => $page->id])->
                andWhere(['block_id' => $this->blocksFind()])
                ->orderBy(['prev_id' => SORT_ASC])->all();
                foreach ($models as $model) 
                {
                    if(!$model->is_hidden)
                    {
                        $values = json_decode($model->json_config_values);
                        if($values->title_element == $this->getVarValue('header', ''))
                        {
                            $link = Url::current([], true) . "#" . ($values->anchor ? $values->anchor : $values->content);
                            $htags = \yii\helpers\Html::a($values->content, \yii\helpers\Url::to($link));  //$this->getTextBetweenTags($values->content,$configs['header']);
                            if(!empty($htags))
                            {
                                $titles[] = $htags;
                            }
                        }
                    }
                }
            }
        }
        $params['items'] = $titles;
        return $this->view->render($this->getViewFileName('php'), $params, $this);
    }

    
    /**
     * 
     * @param type $string
     * @param type $tagname
     * @return type
     */
    private function getTextBetweenTags($string, $tagname)
    {
        $d = new DOMDocument();
        $d->loadHTML($string);
        $return = array();
        foreach ($d->getElementsByTagName($tagname) as $item) 
        {
            
            if(!is_null($item->firstChild) && $item->firstChild->nodeName == "a")
            {
                $return = $d->saveHTML($item->firstChild);
            }else
            {
                $return = $item->textContent;
            }
        }
        return $return;
    }
    
    /**
     * 
     * @return array
     */
    private function blocksFind()
    {
        $ids = [];
        foreach($this->blocks as $block)
        {
            $obj = \luya\cms\models\Block::findOne(['class' => $block]);
            if(!is_null($obj))
            {
                $ids[] = $obj->id;
            }
        }
        return $ids;
    }

}
