<?php

namespace app\modules\cms\models;

use app\modules\cms\admin\Module;
use luya\cms\models\NavItemPage;
use luya\cms\models\NavItemPageBlockItem;
use luya\cms\models\NavItemRedirect;
use luya\helpers\Url;
use Yii;
use yii\helpers\ArrayHelper;

class Nav extends \luya\cms\models\Nav {

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
    public function createPageFromDraft($parentNavId, $navContainerId, $langId, $title, $alias, $description, $fromDraftNavId, $isDraft = false) {
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
     * @param array $other Other attributes
     * @return array|integer If an array is returned the validationed failed, the array contains the error messages. If sucess the nav ID is returned.
     */
    public function createPage($parentNavId, $navContainerId, $langId, $title, $alias, $layoutId, $description, $isDraft = false, $other = []) {
        $_errors = [];

        $nav = $this;
        $navItem = new NavItem();
        $navItem->parent_nav_id = $parentNavId;
        $navItemPage = new NavItemPage();
        if (!empty($other['NavItemRedirect'])) {
            $navItemRedirect = new NavItemRedirect();
        }


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

        if (!empty($other)) {
            if (!empty($other['Nav'])) {
                $nav->attributes = array_merge($nav->attributes, $other['Nav']);
            }
            if (!empty($other['NavItem'])) {
                $navItem->attributes = array_merge($navItem->attributes, $other['NavItem']);
            }

            if (!empty($other['NavItemRedirect'])) {
                $navItem->nav_item_type = 3;
                $navItemRedirect->attributes = array_merge($navItemRedirect->attributes, $other['NavItemRedirect']);
            }
        }

        $navItemPage->attributes = ['nav_item_id' => 0, 'layout_id' => $layoutId, 'create_user_id' => Module::getAuthorUserId(), 'timestamp_create' => time(), 'version_alias' => Module::VERSION_INIT_LABEL];

        if (!$nav->validate()) {
            $_errors = ArrayHelper::merge($nav->getErrors(), $_errors);
        }
        if (!$navItem->validate()) {
            $_errors = ArrayHelper::merge($navItem->getErrors(), $_errors);
        }
        if (!empty($other['NavItemRedirect'])) {
            if (empty($other['NavItemRedirect']) && !$navItemPage->validate()) {
                $_errors = ArrayHelper::merge($navItemPage->getErrors(), $_errors);
            }
            if (!$navItemRedirect->validate()) {
                $_errors = ArrayHelper::merge($navItemRedirect->getErrors(), $_errors);
            }
        }
        if (!empty($_errors)) {
            return $_errors;
        }

        //se c'è il redirect non serve il navitempage
        if (!empty($other['NavItemRedirect'])) {
            $navItemRedirect->save(false);
        } else {
            $navItemPage->save(false); // as validation is done already
        }

        $nav->save(false); // as validation is done already

        $navItem->nav_id = $nav->id;
        if (!empty($navItemRedirect)) {
            $navItem->nav_item_type_id = $navItemRedirect->id;
        } else {
            $navItem->nav_item_type_id = $navItemPage->id;
        }
        $navItemId = $navItem->save(false); // as validation is done already
        $navItemPage->updateAttributes(['nav_item_id' => $navItem->id]);
        return $nav->id;
    }

    /**
     *
     * @return string
     */
    public function getPreviewUrl() {
        $url = Yii::$app->params['platform']['frontendUrl'] . "/cms-page-preview";
        $navItem = NavItem::findOne(['nav_id' => $this->id]);
        if (!is_null($navItem)) {
            $url = Url::appendQueryToUrl($url, ['itemId' => $navItem->id, 'version' => $navItem->nav_item_type_id]);
        }
        return $url;
    }

    public function reindex($e) {

    }

    public function getParents()
    {
        return $this->hasOne(self::class, ['id' => 'parent_nav_id']);
    }
}
