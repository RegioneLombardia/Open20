<?php

namespace open20\amos\core\forms\editors\friendlyRecaptcha;

use open20\amos\core\forms\editors\assets\FriendlyRecaptchaAsset;
use open20\amos\core\forms\editors\assets\FriendlyRecaptchaModuleAsset;
use open20\amos\core\helpers\Html;
use yii\base\Exception;
use yii\base\Widget;
use yii\web\View;
use yii\widgets\InputWidget;

class FriendlyRecaptchaWidget extends InputWidget
{
    /**
     * @var array
     *
     * 'data-callback'  (auto, focus, none : inizia appena si clicca il bottone inizia)
     * 'data-start',
     * 'data-lang',
     * 'data-solution-field-name',
     * 'data-puzzle-endpoint',
     *
     */
    private $pluginOptions = [];
    public $class;
    public $siteKey;
    public $callBack = 'doneCallback';
    public $start = 'none';
    public $lang = 'it';
    public $solutionFieldName;
    public $puzzleEndpoint;

    public function init()
    {
        parent::init();
        $currentLang = \Yii::$app->language;
       if($currentLang == 'it-IT' || $currentLang == 'it' ){
           $this->lang = 'it';
       }elseif($currentLang == 'en-GB' || $currentLang == 'en'){
           $this->lang = 'en';
       }
        if (empty($this->siteKey)) {
            if (!empty(\Yii::$app->params['friendly-recaptcha']['siteKey'])) {
                $this->siteKey = \Yii::$app->params['friendly-recaptcha']['siteKey'];
            }
        }
        if (!empty(\Yii::$app->params['friendly-recaptcha']['puzzleEndpoint'])) {
            $this->puzzleEndpoint = \Yii::$app->params['friendly-recaptcha']['puzzleEndpoint'];
        }

        if (empty($this->siteKey)) {
            throw new Exception("SiteKey is mandatory");
        }

        if ($this->hasModel()) {
            $this->id = !isset($this->options['id']) ? Html::getInputId($this->model,
                $this->attribute) : $this->options['id'];
            $this->name = !isset($this->options['name']) ? \yii\helpers\Html::getInputName($this->model,
                $this->attribute) : $this->options['name'];
            $this->value = !isset($this->options['value']) ? Html::getAttributeValue($this->model,
                $this->attribute) : $this->options['value'];
        }

        if (!empty($this->siteKey)) {
            $this->pluginOptions['data-sitekey'] = $this->siteKey;
        }
        if (!empty($this->callBack)) {
            $this->pluginOptions['data-callback'] = $this->callBack;
        }
        if (!empty($this->start)) {
            $this->pluginOptions['data-start'] = $this->start;
        }
        if (!empty($this->lang)) {
            $this->pluginOptions['data-lang'] = $this->lang;
        }
        if (!empty($this->solutionFieldName)) {
            $this->pluginOptions['data-solution-field-name'] = $this->solutionFieldName;
        }
        if (!empty($this->puzzleEndpoint)) {
            $this->pluginOptions['data-puzzle-endpoint'] = $this->puzzleEndpoint;
        }
        $this->class = 'frc-captcha';
        if (!empty($this->class)) {
            $this->pluginOptions['class'] .= ' ' . $this->class;
        }

    }

    public function run()
    {
        parent::run();
        $this->registerAssets();

        echo $this->renderInputHtml('hidden');
        echo Html::tag('div', '', $this->pluginOptions);
    }

    /**
     * @return void
     */
    public function registerAssets()
    {
        $js = <<<JS
function doneCallback(solution) {
    $('#{$this->id}').val(solution);
}
JS;
        $this->view->registerJs($js, View::POS_HEAD);
        FriendlyRecaptchaModuleAsset::register($this->view);
        FriendlyRecaptchaAsset::register($this->view);

//        $this->view->registerJsFile('https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.14/widget.module.min.js', [
//            'type' => 'module',
//            'async' => true,
//            'defer' => true
//        ]);
//        $this->view->registerJsFile('https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.14/widget.min.js', [
//            'nomodule' => true,
//        ]);

    }

}