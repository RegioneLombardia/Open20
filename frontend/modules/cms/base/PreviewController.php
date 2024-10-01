<?php

namespace app\modules\cms\base;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use luya\cms\models\NavItem;
use luya\cms\menu\InjectItem;
use luya\cms\frontend\base\Controller;
use luya\web\View;
use luya\TagParser;

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
    public function actionIndex($itemId, $version = false, $date = false, $token = null) {
        $navItem = NavItem::findOne($itemId);

        if (!$navItem) {
            throw new NotFoundHttpException("The requested nav item with id {$itemId} does not exist.");
        }

        $validToken = $this->getAccessByToken($navItem->alias, $token);
		
		if(isset($_GET['__tk'])){
			throw new NotFoundHttpException("Unable to find the preview for this ID.");
		}

        if (Yii::$app->adminuser->isGuest && !$validToken) {
            if (\Yii::$app->user->isGuest) {
                return $this->redirect(isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon'] . '?redir=' . urlencode('/cms-page-preview?itemId=' . $itemId . '&version=' . $version) : \Yii::$app->params['platform']['frontendUrl'] . '/' . \open20\amos\admin\AmosAdmin::getModuleName() . '/security/login?url=' . urlencode('/cms-page-preview?itemId=' . $itemId . '&version=' . $version.'&__tk=1'));
            }
            return $this->redirect([
                        '/site/to-menu-url',
                        'url' => Yii::$app->params['platform']['frontendUrl'] . '/admin/login/login-cms-admin?redirect=' . urlencode('/cms-page-preview?itemId=' . $itemId . '&version=' . $version.'&__tk=1'),
            ]);
        }

        $this->setConsumedToken($token);

        $langShortCode = $navItem->lang->short_code;

        Yii::$app->composition['langShortCode'] = $langShortCode;

        $item = Yii::$app->menu->find()->where(['id' => $itemId])->with('hidden')->lang($langShortCode)->one();

        if ($item && !$date && $navItem->nav_item_type_id == $version && !$validToken) {
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

        return $this->renderContent(($validToken ? $this->renderItemElastic($itemId, null, $version) : $this->renderItem($itemId, null, $version)));
    }

    /**
     * 
     * @param string $url
     * @param string $token
     * @return bool
     */
    protected function getAccessByToken($url, $token = null) {
        if (empty($token) || !class_exists('open20\elasticsearch\models\ElasticsearchToken')) {
            return false;
        }

        return \open20\elasticsearch\models\ElasticsearchToken::checkTokens($token, $url);
    }

    /**
     * 
     * @param string $token
     * @return bool
     */
    protected function setConsumedToken($token = null) {
        if (!empty($token) && class_exists('open20\elasticsearch\models\ElasticsearchToken')) {
            return \open20\elasticsearch\models\ElasticsearchToken::consumeTokens($token);
        }
        return true;
    }
    
    /**
     * Render for Elastic
     * @param type $navItemId
     * @param type $appendix
     * @param type $setNavItemTypeId
     * @return \app\modules\cms\base\Response
     * @throws NotFoundHttpException
     */
    public function renderItemElastic($navItemId, $appendix = null, $setNavItemTypeId = false)
    {
        $model = NavItem::find()->where(['id' => $navItemId])->with(['nav'])->one();

        if (!$model) {
            throw new NotFoundHttpException('The requested nav item could not found.');
        }

        Yii::$app->urlManager->contextNavItemId = $navItemId;
        
        $currentMenu = Yii::$app->menu->current;
        
       /* $event = new BeforeRenderEvent();
        $event->menu = $currentMenu;
        foreach ($model->nav->properties as $property) {
            $object = $property->getObject();
            
            $object->trigger($object::EVENT_BEFORE_RENDER, $event);
            if (!$event->isValid) {
                throw new MethodNotAllowedHttpException('Your are not allowed to see this page.');
                return Yii::$app->end();
            }
        }*/
        
        if ($setNavItemTypeId !== false && !empty($setNavItemTypeId)) {
            $model->nav_item_type_id = $setNavItemTypeId;
        }
        
        $typeModel = $model->getType();
       
        if (!$typeModel) {
            throw new NotFoundHttpException("The requestd nav item could not be found with the paired type, maybe this version does not exists for this Type.");
        }
        
        $typeModel->setOptions([
            'navItemId' => $navItemId,
            'restString' => $appendix,
        ]);
        
        $content = $typeModel->getContent();

        if ($content instanceof Response) {
            return Yii::$app->end(0, $content);
        }
        
        // it seems to be a json response as it is an array
        if (is_array($content)) {
            return $content;
        }

        // https://github.com/luyadev/luya/issues/863 - if context controller is not false and the layout variable is not empty, the layout file will be displayed
        // as its already renderd by the module controller itself.
        if ($typeModel->controller !== false && !empty($typeModel->controller->layout)) {
            $this->layout = false;
        }
        
        // If the user has defined a layout file, this will be ensured and set as layout file.
        $layoutFile = $model->nav->layout_file;
        if (!empty($layoutFile)) {
            $this->layout = StringHelper::startsWith($layoutFile, '@') ? $layoutFile : '/' . ltrim($layoutFile, '/');
        }
        
        if ($this->view->title === null) {
            if (empty($model->title_tag)) {
                $this->view->title = $model->title;
            } else {
                $this->view->title = $model->title_tag;
            }
        }

        $this->view->registerMetaTag(['property' => 'og:type', 'content' => 'website'], self::META_OG_TYPE);
        $this->view->registerMetaTag(['name' => 'twitter:card', 'content' => 'summary'], self::META_TWITTER_CARD);
        
        $this->view->registerMetaTag(['property' => 'og:title', 'content' => $this->view->title], self::META_OG_TITLE);
        $this->view->registerMetaTag(['name' => 'twitter:title', 'content' => $this->view->title], self::META_TWITTER_TITLE);
        
        $this->view->registerMetaTag(['property' => 'og:url', 'content' => Yii::$app->request->absoluteUrl], self::META_OG_URL);
        $this->view->registerMetaTag(['name' => 'twitter:url', 'content' => Yii::$app->request->absoluteUrl], self::META_TWITTER_URL);

        if (!empty($model->description)) {
            $this->view->registerMetaTag(['name' => 'description', 'content' => $model->description], self::META_DESCRIPTION);
            $this->view->registerMetaTag(['property' => 'og:description', 'content' => $model->description], self::META_OG_DESCRIPTION);
            $this->view->registerMetaTag(['name' => 'twitter:description', 'content' => $model->description], self::META_TWITTER_DESCRIPTION);
        }
        
        if (!empty($model->keywords)) {
            $this->view->registerMetaTag(['name' => 'keywords', 'content' => implode(", ", $currentMenu->keywords)], self::META_KEYWORDS);
        }

        if (!empty($model->image_id)) {
            $image = Yii::$app->storage->getImage($model->image_id);
            if ($image) {
                $this->view->registerMetaTag(['property' => 'og:image', 'content' => $image->applyFilter(LargeThumbnail::identifier())->sourceAbsolute], self::META_OG_IMAGE);
                $this->view->registerMetaTag(['name' => 'twitter:image', 'content' => $image->applyFilter(LargeThumbnail::identifier())->sourceAbsolute], self::META_TWITTER_IMAGE);
            }
        }
        
        if ($this->module->enableTagParsing) {
            $content = TagParser::convert($content);
        }
        
        if (Yii::$app->has('adminuser') && !Yii::$app->request->isAjax && !Yii::$app->adminuser->isGuest && $this->module->overlayToolbar === true) {
            $this->view->registerCssFile('//fonts.googleapis.com/icon?family=Material+Icons');
            $this->view->on(View::EVENT_BEGIN_BODY, [$this, 'renderToolbar'], ['content' => $content]);
        }
        
        return $content;
    }
}
