<?php

namespace open20\amos\favorites\widgets;

use open20\amos\favorites\AmosFavorites;
use open20\amos\favorites\assets\FavoritesAsset;
use open20\amos\favorites\models\Favorite;
use yii\base\Widget;

class ListFavoriteUrlsWidget extends Widget
{
    public $enableTable = false;

/*
 * I seguenti parametri servono per compatibilità con la sidebar redattore, senza non è possibile personalizzare testo e icona del widget
 * attraverso il model cms-dash-sidebar-item
 */
    public $sidebarTitle = 'Segnalibri';

    public $sidebarMousehoverDescription = 'Segnalibri';

    public $sidebarIcon = 'bookmark';

    public $noFavouriteLabel = '';

    public function init()
    {
        parent::init();
    }

    public function run()
    {

        $module = \Yii::$app->getModule('favorites');
        if ($module->enableFavoritesUrl) {
            $this->registerAsset();

            if(empty($this->noFavouriteLabel)){
               $this->noFavouriteLabel =  AmosFavorites::t('amosfavorites', 'Attualmente non hai selezionato dei preferiti');
            }


            $favorites = Favorite::find()->andWhere(['user_id' => \Yii::$app->user->id])->all();
            if($this->enableTable){
                return $this->render('table_favorites_urls', [
                    'favorites' => $favorites,
                    'noFavouriteLabel' => $this->noFavouriteLabel
                ]);
            }else {
                return $this->render('list_favorites_urls', [
                    'favorites' => $favorites,
                    'listTitle' => $this->sidebarTitle,
                    'listDescription' => $this->sidebarMousehoverDescription,
                    'listIcon' => $this->sidebarIcon
                ]);
            }
        }
        return '';
    }



    /**
     * @return void
     */
    public function registerAsset(){
        FavoritesAsset::register($this->view);
    }

}