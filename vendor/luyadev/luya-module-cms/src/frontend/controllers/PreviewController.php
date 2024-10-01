<?php

namespace luya\cms\frontend\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use luya\cms\models\NavItem;
use luya\cms\menu\InjectItem;
use luya\cms\frontend\base\Controller;

/**
 * CMS Preview Rendering
 *
 * @since 1.0.0
 */
class PreviewController extends Controller {

    /**
     * Renders the preview action.
     *
     * @param integer $itemId The nav item to render.
     * @param integer $version The version to display.
     * @param integer $date The date from the preview frame, is false when not using the preview frame from the cms.
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @return \yii\web\Response|string
     */
    public function actionIndex($itemId, $version = false, $date = false) {
        if (Yii::$app->adminuser->isGuest) {
            if (\Yii::$app->user->isGuest) {
                return $this->redirect(isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon'] . '?redir=' . urlencode('/cms-page-preview?itemId=' . $itemId . '&version=' . $version) : \Yii::$app->params['platform']['frontendUrl'] . '/' . \open20\amos\admin\AmosAdmin::getModuleName() . '/security/login?url=' . urlencode('/cms-page-preview?itemId=' . $itemId . '&version=' . $version));
            }
            return $this->redirect([
                        '/site/to-menu-url',
                        'url' => Yii::$app->params['platform']['frontendUrl'] . '/admin/login/login-cms-admin?redirect=' . urlencode('/cms-page-preview?itemId=' . $itemId . '&version=' . $version),
            ]);
        }

        $navItem = NavItem::findOne($itemId);

        if (!$navItem) {
            throw new NotFoundHttpException("The requested nav item with id {$itemId} does not exist.");
        }

        $langShortCode = $navItem->lang->short_code;

        Yii::$app->composition['langShortCode'] = $langShortCode;

        $item = Yii::$app->menu->find()->where(['id' => $itemId])->with('hidden')->lang($langShortCode)->one();

        if ($item && !$date && $navItem->nav_item_type_id == $version) {
            return $this->redirect($item->link);
        }

        // this item is still offline so we have to inject and fake it with the inject api
        if (!$item) {
            // create new item to inject
            $inject = new InjectItem([
                'id' => $itemId,
                'navId' => $navItem->nav->id,
                'childOf' => Yii::$app->menu->home->id,
                'title' => $navItem->title,
                'alias' => $navItem->alias,
                'isHidden' => true,
            ]);
            // inject item into menu component
            Yii::$app->menu->injectItem($inject);
            // find the inject menu item
            $item = Yii::$app->menu->find()->where(['id' => $inject->id])->with('hidden')->lang($langShortCode)->one();
            // something really went wrong while finding injected item
            if (!$item) {
                throw new NotFoundHttpException("Unable to find the preview for this ID, maybe the page is still Offline?");
            }
        }

        // set the current item, as it would be resolved wrong from the url manager / request path
        Yii::$app->menu->current = $item;

        return $this->renderContent($this->renderItem($itemId, null, $version));
    }

}
