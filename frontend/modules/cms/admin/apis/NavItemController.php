<?php

namespace app\modules\cms\admin\apis;

use Yii;
use luya\cms\models\NavItem;
use luya\cms\admin\Module;
use luya\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\modules\cms\models\NavItemPage;
use app\modules\cms\components\AdminUser;
use luya\admin\models\User;

/**
 * NavItem Api is cached response method to load data and perform changes of cms nav item.
 *
 * @since 1.0.0
 */
class NavItemController extends \luya\cms\admin\apis\NavItemController {

    /**
     * The data api for a nav id and correspoding language.
     *
     * http://example.com/admin/api-cms-navitem/nav-lang-item?access-token=XXX&navId=A&langId=B.
     *
     * @param integer $navId
     * @param integer $langId
     * @return array
     * @throws NotFoundHttpException If the page is not found, a NotFoundHttpException is thrown.
     */
    public function actionNavLangItem($navId, $langId) {
        $item = NavItem::find()->with('nav')->where(['nav_id' => $navId, 'lang_id' => $langId])->one();
        if ($item) {
            $nav = $item->nav;
            $itemToArray = $item->toArray();
            $module = \Yii::$app->getModule('dashboards');

            if (empty($module) || $nav->is_offline == 1 || \Yii::$app->user->can('CMS_PUBLISH_PAGE')) {
                
            } else {

                $draft = \open20\cms\dashboard\utilities\Utility::getMyDraft($item->id);
                if (empty($draft)) {
                    $itemToArray = [];
                } else {
                    $itemToArray['nav_item_type_id'] = $draft->id;
                }
            }
            return [
                'item' => $itemToArray,
                'nav' => $nav->toArray(),
                'typeData' => $item->nav_item_type == 1 ? NavItemPage::getVersionList($item->id) : ArrayHelper::typeCast($item->getType()->toArray()),
            ];
        }

        throw new NotFoundHttpException(Module::t('unable_to_find_item_for_language'));
    }

    /**
     * Get the data for a given placeholder variable inside a page id.
     *
     * @param integer $navItemPageId
     * @param integer $prevId The previous id if its a nested element.
     * @param string $placeholderVar
     */
    public function actionReloadPlaceholder($navItemPageId, $prevId, $placeholderVar) {
        $navItemPage = NavItemPage::findOne($navItemPageId);
        return NavItemPage::getPlaceholder($placeholderVar, $prevId, $navItemPage);
    }

    /**
     * Gets update info (user, timestamp) for the current item.
     *
     * @param integer $navItemPageId
     */
    public function actionUpdateInfo($navItemPageId) {
        $navItem = NavItemPage::findOne($navItemPageId)->navItem;
        $userId = !empty($navItem->update_user_id) ? $navItem->update_user_id : $navItem->create_user_id;
        return [
            "user_id" => $userId,
            "user_first_name" => User::findOne($userId)->firstname,
            "user_last_name" => User::findOne($userId)->lastname,
            "updated_on" => !empty($navItem->timestamp_update) ? $navItem->timestamp_update : $navItem->timestamp_create
        ];
    }

}
