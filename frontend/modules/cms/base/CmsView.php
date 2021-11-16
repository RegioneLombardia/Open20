<?php

namespace app\modules\cms\base;

use yii\helpers\Url;
use yii\helpers\Html;
use Lullabot\AMP\AMP;
use Lullabot\AMP\Validate\Scope;
use Yii;
use luya\Exception;
use luya\helpers\StringHelper;

class CmsView extends \open20\amos\core\components\AmosView
{
    /**
     * @var boolean If csrf validation is enabled in the request component, and autoRegisterCsrf is enabled, then
     * all the meta informations will be auto added to meta tags.
     */
    public $autoRegisterCsrf = true;
    private $queryVars       = [
        'amp',
        'path'
    ];

    /**
     * 
     */
    public function init()
    {
        if (\Yii::$app instanceof \luya\console\Application) {
            $this->autoRegisterCsrf = false;
        }


        parent::init();
        if (empty($this->theme) && Yii::$app->themeManager->hasActiveTheme) {
            $this->theme = Yii::$app->themeManager->activeTheme;
        }

        // auto register csrf tags if enabled
        if ($this->autoRegisterCsrf && Yii::$app->request->enableCsrfValidation) {
            $this->registerCsrfMetaTags();
        }
    }

    public function beforeRender($viewFile, $params)
    {
        $isValid = parent::beforeRender($viewFile, $params);
        return $isValid;
    }

    /**
     * 
     * @param type $view
     * @param type $params
     * @param type $context
     * @return type
     */
    public function render($view, $params = array(), $context = null)
    {
        $html = null;

        if ($this->isAmp()) {
            $this->clearForAmp();
            $amp  = new AMP();
            $amp->loadHtml(parent::render($view, $params, $context),
                ['canonical_path' => Url::canonical(), 'base_url_for_relative_path' => rtrim(Url::home(true),
                        '/'), 'server_url' => rtrim(Url::home(true), '/'), 'url_with_query' => true]);
            $html = $amp->convertToAmpHtml();
        } else {
            $html = parent::render($view, $params, $context);
        }

        return $html;
    }

    /**
     * 
     */
    public function renderHeadHtml()
    {
        $html = null;

        if ($this->isAmp()) {
            $this->clearForAmp();
            $amp  = new AMP();
            $amp->loadHtml(parent::renderHeadHtml(),
                ['canonical_path' => Url::canonical(), 'base_url_for_relative_path' => rtrim(Url::home(true),
                        '/'), 'server_url' => rtrim(Url::home(true), '/'), 'url_with_query' => true]);
            $html = $amp->convertToAmpHtml();
            $html = Html::tag('link', '',
                    ['rel' => 'canonical', 'href' => Url::current(\Yii::$app->controller->actionParams,
                            true)]).PHP_EOL.$html;
        } else {
            $this->registerLinkTag(['rel' => 'amphtml', 'href' => \Yii::$app->menu->current->getAbsoluteLink().'?amp=amp']);
            $html = parent::renderHeadHtml();
        }
        return $html;
    }

    /**
     * 
     * @return boolean
     */
    public function isAmp()
    {
        $is = false;

        foreach ($this->queryVars as $var) {
            $value = \Yii::$app->request->getQueryParam($var);
            if (!empty($value)) {
                if ($value == 'amp') {
                    $is = true;
                }
                break;
            }
        }
        return $is;
    }

    /**
     * 
     */
    private function clearForAmp()
    {
        $this->css          = [];
        $this->cssFiles     = [];
        $this->js           = [];
        $this->jsFiles      = [];
        $this->assetBundles = [];
        $this->linkTags     = [];
    }

    /**
     * Get the url source for an asset.
     *
     * When registering an asset `\app\assets\ResoucesAsset::register($this)` the $assetName
     * is `app\assets\ResourcesAsset`.
     *
     * @param string $assetName The class name of the asset bundle (without the leading backslash)
     * @return string The internal base path to the asset file.
     * @throws Exception
     */
    public function getAssetUrl($assetName)
    {
        $assetName = ltrim($assetName, '\\');

        if (!isset($this->assetBundles[$assetName])) {
            throw new Exception("The AssetBundle '$assetName' is not registered.");
        }

        return $this->assetBundles[$assetName]->baseUrl;
    }

    /**
     * Removes redundant whitespaces (>1) and new lines (>1).
     *
     * @param string $content input string
     * @return string compressed string
     */
    public function compress($content)
    {
        return StringHelper::minify($content);
    }

    /**
     * Return the relativ path to your public_html folder.
     *
     * This wrapper function is commonly used to get the path for images or other files inside your
     * public_html directory. For instance you have put some images in our public folder `public_html/img/luya.png`
     * then you can access the image file inside your view files with:
     *
     * ```php
     * <img src="<?= $this->publicHtml; ?>/img/luya.png" />
     * ```
     *
     * @return string The relative baseUrl to your public_html folder.
     */
    public function getPublicHtml()
    {
        return Yii::$app->request->baseUrl;
    }
}