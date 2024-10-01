<?php

namespace app\modules\cmsapi\frontend\controllers;

use app\modules\cmsapi\frontend\Module as CmsApiModule;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use luya\cms\models\NavItem;
use luya\cms\menu\InjectItem;
use luya\cms\frontend\base\Controller;
use Symfony\Component\DomCrawler\Crawler;

class PreviewController extends Controller
{

    protected function buildPreview($itemId, $version = false, $date = false)
    {
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
            $item   = Yii::$app->menu->find()->where(['id' => $inject->id])->with('hidden')->lang($langShortCode)->one();
            // something really went wrong while finding injected item
            if (!$item) {
                throw new NotFoundHttpException("Unable to find the preview for this ID, maybe the page is still Offline?");
            }
        }

        // set the current item, as it would be resolved wrong from the url manager / request path
        //Yii::$app->menu->current = $item;

        return $this->renderContent($this->renderItem($itemId, null, $version));
    }

    public function preview($itemId, $version = false, $date = false) {
        if (Yii::$app->adminuser->isGuest) {
            throw new ForbiddenHttpException('Unable to see the preview page, session expired or not logged in.');
        }
        return $this->buildPreview($itemId, $version, $date);
    }

    public function actionPreviewContent($itemId, $version = false, $date = false) {
        if (Yii::$app->adminuser->isGuest) {
            throw new ForbiddenHttpException('Unable to see the preview page, session expired or not logged in.');
        }

        $accept = Yii::$app->request->headers->get('Accept');

        $cmsApiModule = \Yii::$app->getModule('cmsapi');

        // Compiles the HTML for the currently selected page...
        $html = $this->buildPreview($itemId, $version, $date);
        // ... and instances the Crawler to parse said HTML.
        $crawler = new Crawler($html);

        $navItem = NavItem::findOne($itemId);

        // Gets the main content DOM tree
        $crawler->filterXPath($cmsApiModule->contentXPathSelector)->each(function (Crawler $crawler) use ($cmsApiModule) {
            // Removes all DOM nodes set to be ignored in the Module configuration
            foreach($cmsApiModule->contentXPathsToIgnore as $path)
                foreach ($crawler->filterXPath($path) as $node)
                    $node->parentNode->removeChild($node);
        });

        $content = $crawler->filterXPath($cmsApiModule->contentXPathSelector)->html('');
        if (empty($content)) throw new ServerErrorHttpException('No valid CMS content for the specified page.');

        if (strpos($accept, 'application/json') !== false) {
            return $this->asJson([
                'title' => (!empty($navItem->title_tag)) ? $navItem->title_tag : $navItem->title,
                'language' => $navItem->lang->short_code,
                'content' => $content,
                'description' => $navItem->description,
                'alias' => $navItem->alias
            ]);
        }

        return preg_replace('/<!--(.*)-->/Uis', '', $content);


    }
}
