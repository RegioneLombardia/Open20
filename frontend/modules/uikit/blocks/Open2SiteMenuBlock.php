<?php

namespace app\modules\uikit\blocks;

use app\modules\cms\models\Nav;
use app\modules\uikit\Module;
use app\modules\backendobjects\frontend\blockgroups\NavigazioneGroup;
use app\modules\uikit\BaseUikitBlock;
use luya\cms\menu\QueryIteratorFilter;
use luya\cms\models\NavItem;
use open20\cms\dashboard\utilities\Utility;
use trk\uikit\Uikit;
use Yii;
use luya\cms\menu\Item;

class Open2SiteMenuBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    protected $component = "open2sitemenu";
    public $cacheEnabled = false;
    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return NavigazioneGroup::className();
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects','block_module_backend_sitemenu');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'menu-open';
    }

    /**
     * @inheritdoc
     */
    public function admin(array $params = array())
    {
        if(!Yii::$app->menu->find()->where(['nav_id' => $this->getVarValue('select_page')['value']])->one()) {
        /** @var Nav $nav */
            $nav = NavItem::findOne(['nav_id' => $this->getVarValue('select_page')['value']]);
            if($nav) {
                return '<p>Pubblicare la pagina ' . $nav->title . ' per visualizzare correttamente il menù contestuale.</p>';
            }
        }
        
        return $this->frontend();
    }


    
    /**
     * 
     * @param array $params
     * @return string
     */
    public function frontend(array $params = array())
    {
        $blockId = $this->getEnvOption('id');
        $params['blockItemId'] = $blockId;

        $configs = $this->getValues();
        $configs["id"] = Uikit::unique($this->component);
        $params['data'] = Uikit::configs($configs);
        $params['debug'] = $this->config();
        $params['filters'] = $this->filters;

        $level = 2;
        $counter = 0;

        // Settaggio level in base a cms-dashboard
        $moduleDashboard = \Yii::$app->getModule('dashboards');
        if (!empty($moduleDashboard)) {
            if ($moduleDashboard->hasProperty('maxCmsPageLevel')) {
                $level = $moduleDashboard->maxCmsPageLevel;
            }
            if(method_exists(Utility::class, 'getPageAncestors')) {
                $startCounter = $counter = count(Utility::getPageAncestors($this->getVarValue('select_page')['value'])) + 1;
            }
        }

        if(!$this->getVarValue('show_all_pages')) {
            $level = $counter;
        }

        $arrayChildren = []; // Array per tenere traccia di tutti i figli raggruppati negli iteratori
        $setAlert=false;

        $currentItem = Yii::$app->menu->find()->where(['nav_id' => $this->getVarValue('select_page')['value']])->one();
        if(!$currentItem) {
            $setAlert=true;
            $currentItem = Yii::$app->menu->getCurrent();
        }

        $parent = $currentItem->parent;
        $items = [];
        /*if($parent){
            $items[] = $parent;
        }*/

        foreach ($currentItem->getSiblings() as $item) {
            if($this->getVarValue('show_siblings')) {
                $items[] = $item;
            }
            if($item->id == $currentItem->id) {
                // Si visitano i figli della pagina corrente
                if(!$this->getVarValue('show_siblings') || $this->getVarValue('show_all_pages')) {
                    $this->exploreChildren($item, $items, $arrayChildren, $counter, $startCounter, $level);
                }
            }else{
                // Si visitano i figli dei fratelli
                if($this->getVarValue('show_siblings') && $this->getVarValue('show_all_pages')) {
                    $this->exploreChildren($item, $items, $arrayChildren, $counter, $startCounter, $level);
                }
            }
        }

        $params['items'] = $items;
        $params['setAlert'] = $setAlert;

        return $this->view->render($this->getViewFileName('php'), $params, $this);
    }

    /** Ritorna un iteratore contenente i figli della pagina di input, discriminando pagine nascoste
     * in base alla configurazione del block
     * @param $item Item
     * @return QueryIteratorFilter
     */
    private function getItemChildren($item) {
        /** @var QueryIteratorFilter $iteratorChildren */
        if($this->getVarValue('show_all_pages')) { // Prendo anche i figli non visibili nel menù
            $iteratorChildren = $item->with('hidden')->children;
        } else {
            $iteratorChildren = $item->children;
        }
        return $iteratorChildren;
    }

    /** Ritorna se la pagina corrente ha figli, discriminando pagine nascoste in base alla configurazione
     * del block
     * @param $item Item
     * @return bool
     */
    private function itemHasChildren($item) {
        if($this->getVarValue('show_all_pages')) {
            return $item->with('hidden')->hasChildren;
        } else {
            return $item->hasChildren;
        }
    }

    /** Esplora e include nell'array $items i figli di una pagina
     * @param $item Item
     * @param $items array
     * @param $arrayChildren array
     * @param $counter int
     * @param $startCounter int
     * @param $level int
     * @return void
     */
    private function exploreChildren($item, &$items, &$arrayChildren, &$counter, $startCounter, $level) {
        // I primi figli stanno sempre al livello 1
        $arrayChildren[$counter] = $this->getItemChildren($item);

        // Continuo finchè l'iteratore di livello 1 non è stato completamente visitato
        while ($arrayChildren[$counter]->getInnerIterator()->valid() || $counter > $startCounter) {
            if (!$arrayChildren[$counter]->getInnerIterator()->valid()) {
                $counter--;
            } else {
                /** @var Item $currentItem */
                $currentItem = $arrayChildren[$counter]->getInnerIterator()->current();

                // Preparo il puntatore per una futura lettura
                $arrayChildren[$counter]->getInnerIterator()->next();

                // Salvo il figlio nell'array
                $items[] = $currentItem;

                // Mi fermo prima in base al livello richiesto
                if ($counter < $level) {
                    if ($this->itemHasChildren($currentItem)) {
                        $counter++;
                        $arrayChildren[$counter] = $this->getItemChildren($currentItem);
                    }
                }
            }
        }
    }
}
