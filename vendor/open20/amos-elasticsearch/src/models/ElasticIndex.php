<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace open20\elasticsearch\models;

use open20\amos\core\utilities\ClassUtility;
use open20\amos\core\utilities\StringUtils;
use open20\elasticsearch\Module;
use yii\base\ArrayableTrait;
use yii\base\BaseObject;

class ElasticIndex extends BaseObject {

    use ArrayableTrait;

    private $module;
    private $model = null;
    private $attributes = [];
    public $index;
    public $type;
    public $id;
    public $routing;
    public $timestamp;
    public $body;

    /**
     * 
     * @var type
     */
    private $language = 'it';

    public function getModel() {
        return $this->model;
    }

    public function setModel($model) {
        $this->model = $model;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function setLanguage($language) {
        $this->language = $language;
    }

    public function init() {
        $this->module = Module::instance();
        if ($this->module->enableI18N) {
            if (empty($this->language)) {
                $this->language = substr(\Yii::$app->language, 0, 2);
            }
        }

        $this->rules();
    }

    /**
     * 
     */
    protected function rules() {
        if ($this->module->enableI18N) {
            $this->attributes = [
                'index', 'type', 'id', 'language', /* 'routing','timestamp', */ 'body'
            ];
        } else {
            $this->attributes = [
                'index', 'type', 'id', /* 'routing','timestamp', */ 'body'
            ];
        }
    }

    /**
     * 
     * @return type
     */
    public function save() {
        $ret = false;
        if ($this->model->canSaveIndex()) {
            if ($this->module->enableI18N) {
                $this->index = $this->getIndexName(strtolower(ClassUtility::getClassBasename($this->model)) . '-' . $this->language);
            } else {
                $this->index = $this->getIndexName(strtolower(ClassUtility::getClassBasename($this->model)));
            }
            $this->type = self::purifyIndexType($this->model->className());
            $this->id = $this->model->primaryKey;
            //$this->routing = 'company_xyz';
            $this->timestamp = strtotime("-1d");
            $this->body = $this->model->toElasticArray();

            $ret = $this->module->client->index($this->toArray($this->attributes));
            $this->module->client->indices()->refresh();
            //$this->setSettings($this->index);
        }
        return $ret;
    }

    /**
     * 
     * @return type
     */
    public function delete() {
        $ret = false;

        if ($this->module->enableI18N) {
            $this->index = $this->getIndexName(strtolower(ClassUtility::getClassBasename($this->model)) . '-' . $this->language);
        } else {
            $this->index = $this->getIndexName(strtolower(ClassUtility::getClassBasename($this->model)));
        }
        $this->type = self::purifyIndexType($this->model->className());
        $this->id = $this->model->primaryKey;
        $objArr = $this->toArray($this->attributes);
        if (array_key_exists('body', $objArr)) {
            unset($objArr['body']);
        }
        $ret = $this->module->client->delete($objArr);
        $this->module->client->indices()->refresh();

        return $ret;
    }

    /**
     * 
     * @param string $type
     * @return string
     */
    public static function purifyIndexType($type) {
        return StringUtils::replace($type, "\\", "_");
    }

    public static function classFromIndexType($type) {
        return StringUtils::replace($type, "_", "\\");
    }

    /**
     * 
     * @param type $index
     * @return type
     */
    protected function setSettings($index) {
        if (!is_null($this->module->defualtIndexSettings)) {
            $params = [
                'index' => $index,
                'body' => [
                    'settings' => $this->module->defualtIndexSettings
                ]
            ];

            $result = $this->module->client->indices()->putSettings($params);
        }
    }

    /**
     * 
     * @param string $index
     * @return string
     */
    protected function getIndexName($index) {
        return $this->module->indexPrefixName . $index;
    }

}
