<?php

namespace app\modules\cmsapi\frontend\utility;

use amos\userauth\frontend\properties\UserAuthProtection;
use app\modules\cms\models\Nav;
use app\modules\cms\models\NavItem;
use app\modules\cmsapi\frontend\models\PostCmsCreatePage;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsBackEndModulesPageBlock;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsDataPageBlock;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsLandingFormPageBlock;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsLayoutSectionPageBlock;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsTextEditorFormPageBlock;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsTextEditorPageBlock;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsUiKitHeadLinePageBlock;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsUikitTextEditorPageBlock;
use DateTime;
use luya\admin\models\Property as AdminProperty;
use luya\cms\models\Property;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class CmsLandigBuilder extends BaseObject
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

    /**
     *
     */
    public function build()
    {
        $nav = Nav::findOne(['id' => $this->postCmsPage->nav_id]);
        if (!is_null($nav)) {
            $nav_item = NavItem::findOne(['nav_id' => $nav->id]);
            if (!is_null($nav_item)) {
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsBackEndModulesPageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsTextEditorPageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsLayoutSectionPageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsLandingFormPageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsUikitTextEditorPageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsUiKitHeadLinePageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsDataPageBlock::class);



                if ($this->postCmsPage->title != $nav_item->title
                    || $this->postCmsPage->alias != $nav_item->alias
                    || $this->postCmsPage->description != $nav_item->description) {
                    $nav_item->alias = $this->postCmsPage->alias;
                    $nav_item->description = $this->postCmsPage->description;
                    $nav_item->title = $this->postCmsPage->title;
                    $ok = $nav_item->save(false);
                }

            }
            $this->pagePublication($nav, $this->postCmsPage);
            $nav->save();
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
        $model->is_offline = 0;
        $openingDate = new DateTime($postCmsPage->event_data->opening_date, new \DateTimeZone('Europe/Rome'));
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

    /**
     *
     */
    public function buidTks()
    {
        if (!empty($this->postCmsPage->form_landing->nav_id_tks_page)) {
            $this->buildFormPages($this->postCmsPage->form_landing->nav_id_tks_page,
                false);
        }
    }

    /**
     *
     */
    public function buildWaiting()
    {
        if (!empty($this->postCmsPage->form_landing->nav_id_wating_page)) {
            $this->buildFormPages($this->postCmsPage->form_landing->nav_id_wating_page,
                false);
        }
    }

    /**
     *
     */
    public function buildAlready()
    {
        if (!empty($this->postCmsPage->form_landing->nav_id_already_present_page)) {
            $this->buildFormPages($this->postCmsPage->form_landing->nav_id_already_present_page,
                false);
        }
    }

    /**
     *
     * @param integer $nav_id
     */
    protected function buildFormPages(int $nav_id, $enableLogin = true)
    {
        $nav = Nav::findOne(['id' => $nav_id]);
        if (!is_null($nav)) {
            $nav_item = NavItem::findOne(['nav_id' => $nav->id]);
            if (!is_null($nav_item)) {
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsTextEditorFormPageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsLayoutSectionPageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsUikitTextEditorPageBlock::class);
                $this->buildBlockModules($nav_item->nav_item_type_id,
                    CmsUiKitHeadLinePageBlock::class);
            }
            $this->pagePublication($nav, $this->postCmsPage, $enableLogin);
            $nav->save();
        }
    }

    /**
     *
     * @param integer $template_id
     */
    public function getDataFromTemplate($template_id)
    {
        $values = [];
        $nav = Nav::findOne(['id' => $template_id]);
        if (!is_null($nav)) {
            $nav_item = NavItem::findOne(['nav_id' => $nav->id]);
            if (!is_null($nav_item)) {
                $values = ArrayHelper::merge($values,
                    $this->getBlockConfigValues($nav_item->nav_item_type_id,
                        CmsDataPageBlock::class));
            }
        }
        return (object)$values;
    }

    /**
     *
     * @param integer $nav_item_page_id
     * @param type $class_
     * @return array
     */
    protected function getBlockConfigValues($nav_item_page_id, $class_)
    {
        $items = [];
        $blocks = $this->findBlockModules($nav_item_page_id, $class_);
        foreach ($blocks as $block) {
            $items = ArrayHelper::merge($items, $block->getCfgValues());
        }
        return $items;
    }
}