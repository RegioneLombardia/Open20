<?php

namespace app\modules\cms\models;

use Yii;
use yii\db\Query;
use yii\base\InvalidConfigException;
use yii\base\ViewContextInterface;
use luya\cms\base\NavItemType;
use luya\cms\base\NavItemTypeInterface;
use luya\cms\admin\Module;
use luya\traits\CacheableTrait;
use luya\cms\models\NavItemPageBlockItem;
use app\modules\cms\components\AdminUser;
use app\modules\cms\models\NavItem;
use app\modules\cms\models\Nav;

/**
 * Represents the type PAGE for a NavItem.
 *
 * @property integer $id
 * @property integer $layout_id
 * @property integer $nav_item_id
 * @property integer $timestamp_create
 * @property integer $create_user_id
 * @property string $version_alias
 * @property Layout $layout
 * @property NavItem $forceNavItem
 *
 * @since 1.0.0
 */
class NavItemPage extends \luya\cms\models\NavItemPage {

    /**
     * Get the list of version/pages for a specific nav item id
     *
     * @param integer $navItemId
     * @return NavItemPage[]
     */
    public static function getVersionList($navItemId) {
        $module = \Yii::$app->getModule('dashboards');
        if (empty($module)) {
            return self::find()->where(['nav_item_id' => $navItemId])->with('layout')->indexBy('id')->orderBy(['id' => SORT_ASC])->all();
        }
        $navItem = NavItem::findOne($navItemId);
        $nav = Nav::findOne($navItem->nav_id);
        if ($nav->is_offline == 1 || \Yii::$app->user->can('CMS_PUBLISH_PAGE')) {
            return self::find()->where(['nav_item_id' => $navItemId])->with('layout')->indexBy('id')->orderBy(['id' => SORT_ASC])->all();
        } else {
            $draft = \open20\cms\dashboard\utilities\Utility::getMyDraft($navItemId);
            if (empty($draft)) {
                return [];
            } else {
                return [$draft->id => $draft];
            }
        }
    }

    /**
     * Get the full array content from all the blocks, placeholders, vars configs and values recursiv for this current NavItemPage (which is layout version for a nav item)
     * @return array
     */
    public function getContentAsArray() {
        //$nav_item_page = (new \yii\db\Query())->select('*')->from('cms_nav_item_page t1')->leftJoin('cms_layout', 'cms_layout.id=t1.layout_id')->where(['t1.id' => $this->id])->one();
        $nav_item_page = $this;

        if (!$nav_item_page->layout) {
            return [];
        }

        $return = [
            'nav_item_page' => ['id' => $nav_item_page->id, 'layout_id' => $nav_item_page->layout_id, 'layout_name' => $nav_item_page->layout->name],
            '__placeholders' => [],
        ];

        $config = json_decode($nav_item_page->layout->json_config, true);

        if (isset($config['placeholders'])) {
            foreach ($config['placeholders'] as $rowKey => $row) {
                foreach ($row as $placeholderKey => $placeholder) {
                    $placeholder['nav_item_page_id'] = $this->id;
                    $placeholder['prev_id'] = 0;
                    $placeholder['__nav_item_page_block_items'] = [];
                    if (!isset($placeholder['cols'])) {
                        $placeholder['cols'] = '12';
                    }

                    $return['__placeholders'][$rowKey][$placeholderKey] = $placeholder;

                    $placeholderVar = $placeholder['var'];

                    $return['__placeholders'][$rowKey][$placeholderKey]['__nav_item_page_block_items'] = self::getPlaceholder($placeholderVar, 0, $this);
                }
            }
        }

        return $return;
    }

    public static function getPlaceholder($placeholderVar, $prevId, \luya\cms\models\NavItemPage $navItemPage) {

        $isAdmin = AdminUser::isAdmin();

        $nav_item_page_block_item_data = NavItemPageBlockItem::find()
                ->where(['prev_id' => $prevId, 'nav_item_page_id' => $navItemPage->id, 'placeholder_var' => $placeholderVar])
                ->orderBy(['sort_index' => SORT_ASC])
                ->with(['block'])
                ->all();

        $data = [];

        foreach ($nav_item_page_block_item_data as $blockItem) {

            $item = self::getBlockItem($blockItem, $navItemPage);

            if ($item) {
                $group = (new \yii\db\Query)->from('cms_block')->leftJoin('cms_block_group', 'cms_block.group_id = cms_block_group.id')->where(['cms_block.id' => $item['block_id']])->one();
                if ($group) {

                    /*if ($group['identifier'] == 'development-group' && !$isAdmin)
                        continue;*/

                    $data[] = $item;
                }
            }

            unset($item);
        }

        return $data;
    }

}
