<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\seo
 * @category   CategoryName
 */

namespace open20\amos\seo\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;


class AnalyzerWidget extends Widget
{
    public $autoUpdate = true;
    public $baseUrl;
    public $fields = [];
    public $id = 'amos-seo-analyzer';
    public $locale;
    public $model;
    public $options = [];

    public function init() {
        parent::init();
        $this->locale = (empty($this->locale)) ? \Yii::$app->language : $this->locale;
        $this->baseUrl = (empty($this->baseUrl)) ? \Yii::$app->params['platform']['backendUrl'] : $this->baseUrl;
        $this->setFields();
        $this->setContentModel();
    }

    protected function setFields() {
        $this->fields = ArrayHelper::merge([
            'keyword' => ['selector' => '#'.$this->id.'-keyphrase', 'event' => 'input'],
            'synonyms' => ['selector' => '#'.$this->id.'-synonyms', 'event' => 'input'],
        ], $this->fields);
    }

    protected function setContentModel() {

    }

    public function run() {
        return $this->render('analyzer', [
            'autoUpdate' => $this->autoUpdate,
            'baseUrl' => $this->baseUrl,
            'fields' => $this->fields,
            'id' => $this->id,
            'locale' => $this->locale,
            'options' => $this->options
        ]);
    }

}
