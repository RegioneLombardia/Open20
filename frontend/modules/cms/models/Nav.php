<?php
namespace app\modules\cms\models;

use app\modules\cms\admin\Module;
use luya\cms\models\NavItemPage;
use luya\cms\models\NavItemPageBlockItem;
use luya\helpers\Url;
use Yii;
use yii\helpers\ArrayHelper;

class Nav extends \luya\cms\models\Nav
{
    /**
     * Create a page from a from a draft.
     *
     * @param integer $parentNavId
     * @param integer $navContainerId
     * @param integer $langId
     * @param string $title
     * @param string $alias
     * @param string $description
     * @param integer $fromDraftNavId
     * @param string $isDraft
     * @return boolean|array If an array is returned, the creation had an error, the array contains the messages.
     */
    public function createPageFromDraft($parentNavId, $navContainerId, $langId, $title, $alias, $description, $fromDraftNavId, $isDraft = false)
    {
        if (!$isDraft && empty($isDraft) && !is_numeric($isDraft)) {
            $isDraft = 0;
        }

        $errors = [];
        // nav
        $nav = $this;
        $nav->attributes = [
            'parent_nav_id' => $parentNavId,
            'nav_container_id' => $navContainerId,
            'is_hidden' => true,
            'is_offline' => true,
            'is_draft' => $isDraft
        ];
        // nav item
        $navItem = new NavItem();
        $navItem->parent_nav_id = $parentNavId;
        $navItem->attributes = [
            'lang_id' => $langId,
            'title' => $title,
            'alias' => $alias,
            'description' => $description,
            'nav_item_type' => 1
        ];

        if (!$nav->validate()) {
            $errors = ArrayHelper::merge($nav->getErrors(), $errors);
        }
        if (!$navItem->validate()) {
            $errors = ArrayHelper::merge($navItem->getErrors(), $errors);
        }

        if (empty($fromDraftNavId)) {
            $errors['from_draft_id'] = [Module::t('model_navitempage_empty_draft_id')];
        }

        if (!empty($errors)) {
            return $errors;
        }

        // get draft nav item data
        $draftNavItem = NavItem::findOne(['nav_id' => $fromDraftNavId]);

        $navItemPageId = $draftNavItem->type->id;
        $layoutId = $draftNavItem->type->layout_id;
        $pageBlocks = NavItemPageBlockItem::findAll(['nav_item_page_id' => $navItemPageId]);

        // proceed save process
        $nav->save();
        $navItemPage = new NavItemPage();
        $navItemPage->layout_id = $layoutId;
        $navItemPage->timestamp_create = time();
        $navItemPage->version_alias = Module::VERSION_INIT_LABEL;
        $navItemPage->create_user_id = Module::getAuthorUserId();
        $navItemPage->nav_item_id = 0;

        if (!$navItemPage->validate()) {
            return $navItemPage->getErrors();
        }

        $navItemPage->save();

        $idLink = [];
        foreach ($pageBlocks as $block) {
            $i = new NavItemPageBlockItem();
            $i->attributes = $block->attributes;
            $i->nav_item_page_id = $navItemPage->id;
            $i->insert();
            $idLink[$block->id] = $i->id;
        }

        // check if prev_id is used, check if id is in set - get new id and set new prev_ids in copied items
        $newPageBlocks = NavItemPageBlockItem::findAll(['nav_item_page_id' => $navItemPage->id]);
        foreach ($newPageBlocks as $block) {
            if ($block->prev_id && isset($idLink[$block->prev_id])) {
                $block->updateAttributes(['prev_id' => $idLink[$block->prev_id]]);
            }
        }

        $navItem->nav_id = $nav->id;
        $navItem->nav_item_type_id = $navItemPage->id;

        $navItem->save();

        $navItemPage->updateAttributes(['nav_item_id' => $navItem->id]);

        return true;
    }

    /**
     * Create a new page.
     *
     * @param integer $parentNavId
     * @param integer $navContainerId
     * @param integer $langId
     * @param string $title
     * @param string $alias
     * @param integer $layoutId
     * @param string $description
     * @param string $isDraft
     * @return array|integer If an array is returned the validationed failed, the array contains the error messages. If sucess the nav ID is returned.
     */
    public function createPage($parentNavId, $navContainerId, $langId, $title, $alias, $layoutId, $description, $isDraft = false)
    {
        $_errors = [];

        $nav = $this;
        $navItem = new NavItem();
        $navItem->parent_nav_id = $parentNavId;
        $navItemPage = new NavItemPage();

        if (!$isDraft && empty($isDraft) && !is_numeric($isDraft)) {
            $isDraft = 0;
        }

        $nav->attributes = [
            'parent_nav_id' => $parentNavId,
            'nav_container_id' => $navContainerId,
            'is_hidden' => true,
            'is_offline' => true,
            'is_draft' => $isDraft
        ];

        $navItem->attributes = [
            'lang_id' => $langId,
            'title' => $title,
            'alias' => $alias,
            'description' => $description,
            'nav_item_type' => 1
        ];

        $navItemPage->attributes = ['nav_item_id' => 0, 'layout_id' => $layoutId, 'create_user_id' => Module::getAuthorUserId(), 'timestamp_create' => time(), 'version_alias' => Module::VERSION_INIT_LABEL];

        if (!$nav->validate()) {
            $_errors = ArrayHelper::merge($nav->getErrors(), $_errors);
        }
        if (!$navItem->validate()) {
            $_errors = ArrayHelper::merge($navItem->getErrors(), $_errors);
        }
        if (!$navItemPage->validate()) {
            $_errors = ArrayHelper::merge($navItemPage->getErrors(), $_errors);
        }

        if (!empty($_errors)) {
            return $_errors;
        }

        $navItemPage->save(false); // as validation is done already
        $nav->save(false); // as validation is done already

        $navItem->nav_item_type_id = $navItemPage->id;
        $navItem->nav_id = $nav->id;
        $navItemId = $navItem->save(false); // as validation is done already

        $navItemPage->updateAttributes(['nav_item_id' => $navItem->id]);

        return $nav->id;
    }

    /**
     *
     * @return string
     */
    public function getPreviewUrl(){
        $url = Yii::$app->params['platform']['frontendUrl'] ."/cms-page-preview";
        $navItem = NavItem::findOne(['nav_id' => $this->id]);
        if(!is_null($navItem)){
            $url = Url::appendQueryToUrl($url, ['itemId' => $navItem->id, 'version' => $navItem->nav_item_type_id]);
        }
        return $url;
    }
}