<?php

namespace open20\amos\favorites\widgets;

use open20\amos\favorites\assets\FavoritesAsset;
use open20\amos\favorites\models\Favorite;
use yii\base\Widget;

class SelectFavoriteUrlsWidget extends Widget
{
    public $id;
    //is not mandatory
    public $model = null;
    public $isCms = false;
    public $positionRelative = false;

    public $url = null;
    public $title = null;
    public $module = null;
    public $controller = null;
    public $classname = null;
    public $content_id = null;

    public function init()
    {
        parent::init();
        if (empty($this->id)) {
            $this->id = $this->getId();
        }
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $module = \Yii::$app->getModule('favorites');
        if ($module->enableFavoritesUrl && !\Yii::$app->user->isGuest && \Yii::$app->user->can('FAVORITE_READER')) {
            if (!empty($this->classname) && !in_array($this->classname, $module->modelsEnabledFavoritesUrl)) {
                return '';
            }
            $this->registerAsset();
            $this->initFavorite();

            return $this->render('select_favorites_urls', [
                'url' => $this->url,
                'title' => $this->title,
                'module' => $this->module,
                'controller' => $this->controller,
                'classname' => $this->classname,
                'content_id' => $this->content_id,
                'is_selected' => $this->isUrlSelected($this->url),
                'idWidget' => $this->id,
                'widget' => $this
            ]);
        }
        return '';
    }

    /**
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function initFavorite()
    {
        if (empty($this->module)) {
            $this->module = \Yii::$app->controller->module->id;
        }

        if (empty($this->url)) {
            $this->url = \Yii::$app->request->getUrl();
        }
        if (empty($this->controller)) {
            $this->controller = \Yii::$app->controller->id;
        }

        if (empty($this->title)) {
            $title = \Yii::$app->controller->view->title;
            if (empty($title)) {
                $title = \Yii::$app->controller->view->params['titleSection'];
            }
            $this->title = $title;
        }

        if (!empty($this->model)) {
            $this->classname = get_class($this->model);
            if (!$this->model->isNewRecord) {
                $this->content_id = $this->model->id;
            }
        }
    }

    /**
     * @param $url
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function isUrlSelected($url)
    {
        $count = Favorite::find()->andWhere(['url' => $url, 'user_id' => \Yii::$app->user->id])->count();
        return $count > 0;

    }

    /**
     * @return void
     */
    public function registerAsset()
    {
        FavoritesAsset::register($this->view);
    }


}