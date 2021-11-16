<?php

namespace app\modules\sitemap\frontend\controllers;

use luya\cms\helpers\Url;
use luya\cms\models\Nav;
use luya\cms\models\NavItem;
use luya\web\Controller;
use samdark\sitemap\Sitemap;
use Yii;

class SitemapController extends Controller
{
    public function actionIndex()
    {     
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
    private function buildSitemapfile($sitemapFile)
    {
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
            /*$query = Nav::find()->andWhere([
                'is_deleted' => false,
                'is_offline' => false,
                'is_draft' => false,
            ])->with(['navItems', 'navItems.lang']);*/
            foreach(Yii::$app->menu->findAll([]) as $nav) {
                /** @var Nav $nav */
                $urls = [];
                //foreach($nav->navItems as $navItem) {
                    /** @var NavItem $navItem */
                    $urls[$navItem->lang->short_code] = Yii::$app->request->hostInfo . $nav->link;
                //}
                $lastModified = $navItem->dateUpdated == 0 ? $navItem->dateCreated : $navItem->dateUpdated;
                if(!is_null($urls)){
                    try{
                        $sitemap->addItem($urls, $lastModified);
                    }catch(\Exception $ex){
                    
                    }
                }
            }
        }
        
        if(Yii::$app->getModule('backendobjects')){
            $module = Yii::$app->getModule('backendobjects');
            $modelsMap = [];
            foreach($module->getPublishedModelClasses() as $navItemPageBlockItemId => $searchClass){
                $modelSearch = new $searchClass;
                $models = $modelSearch->cmsSearch([])->getModels();
                foreach($models as $model){
                    $modelsMap[$model->getPrettyUrl()]['blockItemIds'][] = $navItemPageBlockItemId;
                    $modelsMap[$model->getPrettyUrl()]['lastUpdate'] = strtotime($model->updated_at);
                }
            }
            foreach($modelsMap as $prettyUrl => $data){
                $urls = [];
                foreach($data['blockItemIds'] as $blockItemId){
                    $urls = array_merge($urls,$module->getSeoUrl($prettyUrl,$blockItemId, true));
                }
                try{
                    $sitemap->addItem($urls,$data['lastUpdate']);
                }catch(\Exception $ex){
                    
                }
            }
        }
        // write sitemap files
        $sitemap->write();
    }
}