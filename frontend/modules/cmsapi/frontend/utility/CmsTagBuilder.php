<?php

namespace app\modules\cmsapi\frontend\utility;

use amos\userauth\frontend\properties\UserAuthProtection;
use app\modules\cms\models\Nav;
use app\modules\cms\models\NavItem;
use app\modules\cmsapi\frontend\models\PostCmsCreatePage;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsBackEndModulesPageBlock;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsPhpPanlePageBlock;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsUiKitHeadLinePageBlock;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsUikitTextEditorPageBlock;
use DateTime;
use DateTimeZone;
use luya\admin\models\Property as AdminProperty;
use luya\cms\models\Property;
use yii\base\BaseObject;



class CmsTagBuilder extends BaseObject
{
    
    
    private $postCmsPage;

    /**
     *
     * @return PostCmsCreatePage
     */
    public function getPostCmsPage(): PostCmsCreatePage
    {
        return $this->postCmsPage;
    }
    
    /**
     *
     * @param PostCmsCreatePage $postCmsPage
     */
    public function setPostCmsPage(PostCmsCreatePage $postCmsPage)
    {
        $this->postCmsPage = $postCmsPage;
    }

    /**
     *
     * @param type $config
     * @param PostCmsCreatePage $postCmsPage
     */
    public function __construct($config = array(),
                                PostCmsCreatePage &$postCmsPage = null)
    {
        parent::__construct($config);
        $this->postCmsPage = $postCmsPage;
    }
    
    
    public function build()
    {
        $nav = Nav::findOne(['id' => $this->postCmsPage->nav_id]);
        if (!is_null($nav)) {
            $nav_item = NavItem::findOne(['nav_id' => $nav->id]);
            if (!is_null($nav_item)) {
                
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsUikitTextEditorPageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsUiKitHeadLinePageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsBackEndModulesPageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                        CmsPhpPanlePageBlock::class);

                if ($this->postCmsPage->title != $nav_item->title
                    || $this->postCmsPage->alias != $nav_item->alias
                    || $this->postCmsPage->description != $nav_item->description) {
                    $nav_item->alias = $this->postCmsPage->alias;
                    $nav_item->description = $this->postCmsPage->description;
                    $nav_item->title = $this->postCmsPage->title;
                    $ok = $nav_item->save(false);
                }

            }
            $this->pagePublication($nav, $this->postCmsPage, false);
            $nav->save(false);
        }
    }
    
    /**
     *
     * @param type $nav_item_page_id
     *
     */
    protected function buildBlockModules($nav_item_page_id, $class_)
    {
        $blocks = $this->findBlockModules($nav_item_page_id, $class_);
        foreach ($blocks as $block) {
            $block->buildValues($this->postCmsPage);
            $block->save();
        }
    }

    /**
     *
     * @param type $nav_item_page_id
     * @param type $class_
     * @return type
     */
    protected function findBlockModules($nav_item_page_id, $class_)
    {
        $blocks = $class_::findBlocks($nav_item_page_id);
        return $blocks;
    }

    /**
     *
     * @param Nav $model
     * @param PostCmsCreatePage $postCmsPage
     */
    protected function pagePublication(Nav $model,
                                       PostCmsCreatePage $postCmsPage,
                                       $enableLogin = true)
    {
        $model->is_hidden = 0;
        $model->is_offline = 1;
        $openingDate = new DateTime($postCmsPage->event_data->opening_date, new DateTimeZone('Europe/Rome'));
        $model->publish_from = !empty($postCmsPage->event_data->opening_date) ? $openingDate->getTimestamp()
            : new DateTime();

        $nav_property = new UserAuthProtection();
        $admin_prop = AdminProperty::findOne(['var_name' => $nav_property->varName()]);
        if (!is_null($admin_prop)) {
            $property = Property::findOne(['admin_prop_id' => $admin_prop->id,
                'nav_id' => $model->id]);
            if (!is_null($property)) {
                $property->value = $enableLogin ? $postCmsPage->with_login : false;
                $property->save();
            } else {
                $property = new Property();
                $property->nav_id = $model->id;
                $property->admin_prop_id = $admin_prop->id;
                $property->value = $enableLogin ? $postCmsPage->with_login
                    : false;
                $property->save();
            }
        }
    }
}
