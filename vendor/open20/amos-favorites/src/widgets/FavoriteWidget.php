<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\favorites\widgets
 * @category   CategoryName
 */

namespace open20\amos\favorites\widgets;

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\favorites\AmosFavorites;
use open20\amos\favorites\exceptions\FavoritesException;
use open20\amos\notificationmanager\AmosNotify;

use Yii;
use yii\base\Widget;
use yii\web\View;
use yii\helpers\Url;

/**
 * Class FavoriteWidget
 *
 * Widget to show the favorite icon.
 *
 * @package open20\amos\favorites\widgets
 */
class FavoriteWidget extends Widget
{
    public $layout = '{beginContainerSection}{favoriteButton}{endContainerSection}';

    /**
     * @var \open20\amos\core\record\Record $model
     */
    public $model;

    /**
     * @var array $containerOptions Default to ['id' => 'favorite-container-MODEL_ID']
     */
    public $containerOptions = [];

    /**
     * @var bool $isAlreadyFavorite
     */
    protected $isAlreadyFavorite = false;

    /**
     * @var $notify Motify Module
     */
    protected $notify;

    /**
     * @throws FavoritesException
     */
    public function init()
    {
        parent::init();

        if (is_null($this->model)) {
            throw new FavoritesException(AmosFavorites::t('amosfavorites', '#widget_model_required'));
        }

        if ($this->model->isNewRecord) {
            return '';
        }

        /** @var AmosFavorites $favorites */
        $favorites = \Yii::$app->getModule('favorites');
        if(!$favorites->enableFavoritesUrl){
            return '';
        }
        if (!isset($favorites->modelsEnabled) || !in_array($this->model->className(), $favorites->modelsEnabled)) {
            return false;
        }

        $this->notify = Yii::$app->getModule('notify');
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->initDefaultOptions();

        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);

            return $content === false ? $matches[0] : $content;
        }, $this->layout);

        $this->registerWidgetJs();

        return $content;
    }

    /**
     * Set default options values
     */
    private function initDefaultOptions()
    {
        $this->containerOptions['id'] = 'favorite-container-' . $this->model->id;
        $this->containerOptions['class'] = 'pull-left favorites-container';
    }

    /**
     * Return the container id
     * @return string
     */
    public function getContainerId()
    {
        return $this->containerOptions['id'];
    }

    /**
     * Renders a section of the specified name
     * If the named section is not supported, false will be returned
     *
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{beginContainerSection}':
                return $this->renderBeginContainerSection();
            case '{favoriteButton}':
                return $this->favoriteButton();
            case '{endContainerSection}':
                return $this->renderEndContainerSection();
            default:
                return false;
        }
    }

    /**
     * This method render the beginning part of the container
     *
     * @return string
     */
    protected function renderBeginContainerSection()
    {
        return Html::beginTag('div', $this->containerOptions);
    }

    /**
     * Method that render the section of the comment container
     *
     * @return string
     */
    public function favoriteButton()
    {
        if (Yii::$app->user->isGuest) {
            return Html::tag('p',
                AmosFavorites::t('amosfavorites', '#login_required'),
                ['class' => 'not-logged-message']
            );


        }

        /*
        Modifica da uso AmosIcons::show (... md-star ...)
        Al momento usa una outline per non preferito e stellina piena per quella preferita.
        La classe favorite-customization è fatta apposta per gestire personalizzazioni sulla dimensione.
        Si può definire esternamente a piacimento con font-size.
        */

        $this->isAlreadyFavorite = $this->notify->isFavorite($this->model, Yii::$app->user->id);
        $star_class = $this->isAlreadyFavorite ? 'mdi-star' : 'mdi-star-outline';
        return Html::a(
            "<span class='favorite-customization mdi ".$star_class."' id='".$this->favoriteIconId()."'></span>",
            null,
            [
                'title' => self::favoriteBtnTitle($this->isAlreadyFavorite),
                'id' => $this->favoriteBtnId()
            ]
        );
    }

    /**
     * This method render the end part of the container
     *
     * @return string
     */
    protected function renderEndContainerSection()
    {
        return Html::endTag('div');
    }

    /**
     * Return the favorite button title
     *
     * @return string
     */
    public static function favoriteBtnTitle($isAlreadyFavorite = false)
    {
        return $isAlreadyFavorite
            ? AmosFavorites::t('amosfavorites', '#remove_favorite')
            : AmosFavorites::t('amosfavorites', '#add_to_favorites')
        ;
    }

    /**
     * Return the favorite button ID
     *
     * @return string
     */
    private function favoriteBtnId()
    {
        return 'favorite-btn-id-' . $this->model->id;
    }

    /**
     * Return the favorite icon ID
     *
     * @return string
     */
    private function favoriteIconId()
    {
        return 'favorite-icon-id-' . $this->model->id;
    }

    /**
     * This method registers all widget javascript
     */
    private function registerWidgetJs()
    {
        $alreadyFavorite = $this->isAlreadyFavorite ? 1 : 0;
        $beUrl = Url::to(['/favorites/favorite/favorite']);

        $js = "
        var disableFavoriteClick" . $this->model->id . " = 0;
        $('#" . $this->favoriteBtnId() . "').on('click', function(event) {
            if (disableFavoriteClick" . $this->model->id . " == 1) {
                return false;
            } else {
                disableFavoriteClick" . $this->model->id . " = 1;
            }
            event.preventDefault();
            var params = {};
            params.className = '" . addslashes($this->model->className()) . "';
            params.id = " . $this->model->id . ";
            params._csrf = '" . Yii::$app->request->getCsrfToken() . "',

            $.ajax({
                url: '" . $beUrl . "',
                data: params,
                type: 'post',
                dataType: 'json',
                complete: function (jqXHR, textStatus) {
                    disableFavoriteClick" . $this->model->id . " = 0;
                },
                success: function (response) {
//                    response = JSON.parse(response);

                    if (response.success == 1) {
                        if (response.nowFavorite == 1) {
                            $('#" . $this->favoriteIconId() . "').addClass('favorite');
                            $('#" . $this->favoriteIconId() . "').addClass('mdi-star');
                            $('#" . $this->favoriteIconId() . "').removeClass('mdi-star-outline');
                        } else if (response.nowNotFavorite == 1) {
                            $('#" . $this->favoriteIconId() . "').removeClass('favorite');
                            $('#" . $this->favoriteIconId() . "').addClass('mdi-star-outline');
                            $('#" . $this->favoriteIconId() . "').removeClass('mdi-star');
                            
                        }
                        $('#" . $this->favoriteBtnId() . "').prop('title', response.favoriteBtnTitle);
                    }

                    // alert(response.msg);
                },
                error: function (response) {
                    alert('Favorite AJAX error');
                }
            });
            return false;
        });

        $('#" . $this->favoriteBtnId() . "').prop('title', '" . self::favoriteBtnTitle($this->isAlreadyFavorite) . "');
        if (" . $alreadyFavorite . " == 1) {
            $('#" . $this->favoriteIconId() . "').addClass('favorite');
        } else {
            $('#" . $this->favoriteIconId() . "').removeClass('favorite');
        }
        ";

        Yii::$app->view->registerJs($js, View::POS_READY);
    }
}
