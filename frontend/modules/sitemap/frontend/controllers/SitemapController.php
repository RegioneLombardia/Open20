<?php

namespace app\modules\sitemap\frontend\controllers;

use luya\cms\helpers\Url;
use luya\cms\models\Nav;
use luya\cms\models\NavItem;
use luya\web\Controller;
use samdark\sitemap\Sitemap;
use Yii;

class SitemapController extends Controller {

    public function actionIndex() {
        $sitemapFile = Yii::getAlias('@runtime/sitemap.xml');
        // update sitemap file as soon as CMS structure changes
        $lastCmsChange = max(NavItem::find()->select(['MAX(timestamp_create) as tc', 'MAX(timestamp_update) as tu'])->asArray()->one());
        if (true || !file_exists($sitemapFile) || filemtime($sitemapFile) < $lastCmsChange) { #TODO: gestire caching
            $this->buildSitemapfile($sitemapFile);
        }
        return Yii::$app->response->sendFile($sitemapFile, null, [
                    'mimeType' => 'text/xml',
                    'inline' => true,
        ]);
    }

    private function buildSitemapfile($sitemapFile) {
        $baseUrl = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl;
        // create sitemap
        $sitemap = new Sitemap($sitemapFile, true);
        // ensure sitemap is only one file
        // TODO make this configurable and allow more than one sitemap file
        $sitemap->setMaxUrls(PHP_INT_MAX);
        // add entry page
        $sitemap->addItem($baseUrl);
        // add luya CMS pages
        if ($this->module->module->hasModule('cms')) {
            // TODO this does not reflect time contraints for publishing items
            /* $query = Nav::find()->andWhere([
              'is_deleted' => false,
              'is_offline' => false,
              'is_draft' => false,
              ])->with(['navItems', 'navItems.lang']); */
            \Yii::$app->language = 'it-IT';
            foreach (Yii::$app->menu->find()->andWhere(['parent_nav_id' => 0])->andWhere(['is_offline' => 0])->all() as $nav) {
                /** @var Nav $nav *//*
                  $urls                             = null;


                  /** @var NavItem $navItem */
                $urls = ((strpos($nav->link, Yii::$app->request->hostInfo) !== false) ? '' : Yii::$app->request->hostInfo) . $nav->link;

                $lastModified = $nav->dateUpdated == 0 ? $nav->dateCreated : $nav->dateUpdated;
                if (!is_null($urls)) {
                    try {
                        $sitemap->addItem($urls, $lastModified);
                    } catch (\Exception $ex) {
                        
                    }
                }
            }
        }


//example for other contents
//        $block = \Yii::$app->db
//                ->createCommand("SELECT * FROM cms_nav_item_page_block_item WHERE json_config_values like '%AmosEvents%' AND is_hidden = 0 AND json_config_values like '%listEventHomepageRestyle%'")
//                ->queryOne();
//        if (!empty($block['id'])) {
//            $eventi = \open20\amos\events\models\Event::find()
//                    ->andWhere(['status' => 'EventWorkflow/PUBLISHED'])
//                    ->andWhere(['primo_piano' => 1])
//                    ->all();
//
//            foreach ($eventi as $ev) {
//                $url = \Yii::$app->getModule('backendobjects')::getSeoUrl($ev->getPrettyUrl(), $block['id']);
//                $sitemap->addItem($url, strtotime($ev->updated_at));
//            }
//        }
//
//
//        $categorie = \open20\amos\pages\models\Categorie::find()
//                ->andWhere(['visibile' => 1])
//                ->all();
//        foreach ($categorie as $cat) {
//            $sitemap->addItem(\Yii::$app->params['platform']['frontendUrl'] . $cat->getFullViewUrl(), strtotime($cat->updated_at));
//        }
//
//        $pagine = \open20\amos\pages\models\Pagine::find()
//                ->andWhere(['visibile' => 1])
//                ->all();
//
//        foreach ($pagine as $pag) {
//            $sitemap->addItem(\Yii::$app->params['platform']['frontendUrl'] . $pag->getFullViewUrl(), strtotime($pag->updated_at));
//        }
        // write sitemap files
        $sitemap->write();
    }

}
