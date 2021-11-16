<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation
 * @category   CategoryName
 */

namespace open20\amos\translation\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use open20\amos\core\record\CachedActiveQuery;

class TranslateableBehavior extends Behavior
{
    const DELETE_ONLY_LANGUAGE_SELECTED = 0;
    const DELETE_ALL                    = 1;

    /**
     * @var string the name of the translations relation
     */
    public $relation = 'translations';

    /**
     * @var string the language field used in the related table. Determines the language to query | save.
     */
    public $languageField = 'language';

    /**
     * @var array the list of attributes to translate. You can add validation rules on the owner.
     */
    public $translationAttributes      = [];
    public $defaultLanguage; // = 'en-GB';
    public $enableValidationAttributes = false;
    public $blackListAttributes        = [];
    public $forceTranslation           = false;
    public $pathsTranslation           = ['@frontend'];
    public $statusWorkflowApproved     = 'AmosTranslationWorkflow/APPROVED';
    public $statusWorkflowInitial      = 'AmosTranslationWorkflow/DRAFT';
    public $enableWorkflow             = false;
    public $afterDeleteSource          = self::DELETE_ALL;
    public $workflowBehavior           = 'workflow';
    public $workflow                   = 'AmosTranslationWorkflow';
    private $systemBlackListAttributes = ['id', 'status', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by',
        'deleted_by'];

    /**
     * @var ActiveRecord[] the models holding the translations.
     */
    private $_models = [];

    /**
     * @var string the language selected.
     */
    private $_language;

    public function init()
    {
        parent::init();
        $module = \Yii::$app->getModule('translation');
        if (!empty($module->defaultLanguage) && empty($this->defaultLanguage)) {
            $this->defaultLanguage = $module->defaultLanguage;
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_INIT => 'afterInit',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
        ];
    }

    /**
     * Make [[$translationAttributes]] writable
     */
    public function __set($name, $value)
    {
        if (in_array($name, $this->translationAttributes)) {
            $this->getTranslation()->$name = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * Make [[$translationAttributes]] readable
     * @inheritdoc
     */
    public function __get($name)
    {
        if (!in_array($name, $this->translationAttributes) && !isset($this->_models[$name])) {
            return parent::__get($name);
        }
        if (isset($this->_models[$name])) {
            return $this->_models[$name];
        }
        $model = $this->getTranslation();
        return $model->$name;
    }

    /**
     * Expose [[$translationAttributes]] writable
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return in_array($name, $this->translationAttributes) ? true : parent::canSetProperty($name, $checkVars);
    }

    /**
     * Expose [[$translationAttributes]] readable
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return in_array($name, $this->translationAttributes) ? true : parent::canGetProperty($name, $checkVars);
    }

    /**
     * @param \yii\base\Event $event
     */
    public function afterFind($event)
    {
        $this->setLanguage(\Yii::$app->language);
        $this->populateTranslations();
        $this->getTranslation($this->getLanguage());
        if ($this->forceTranslation && !empty(\Yii::$app->controller)) {
            $viewPath      = \Yii::$app->controller->getViewPath();
            $currentAction = (!empty($viewPath) ? trim($viewPath) : '').DIRECTORY_SEPARATOR.(!empty(\Yii::$app->controller->action->id)
                    ? trim(\Yii::$app->controller->action->id) : '');
            $allPath       = $this->allPath();
            $trimmedAction = trim($currentAction);
            if (!empty($trimmedAction) && (in_array('*', $this->pathsTranslation) || in_array($currentAction, $allPath))) {
                $this->translateOriginalValues();
            }
        }
    }

    /**
     *
     * @return array
     */
    private function allPath()
    {
        $allPath = [];
        $paths   = $this->pathsTranslation;
        clearstatcache();
        if (!empty($paths)) {
            foreach ((array) $paths as $value) {
                $path = $this->normalizePath(\Yii::getAlias($value));
                if (is_dir($path)) {
                    $pathsDir = $this->normalizePath(FileHelper::findFiles($path));
                    foreach ($pathsDir as $dir) {
                        $allPath[] = $dir;
                    }
                } else if (is_file($path.'.php')) {
                    $allPath[] = $this->normalizePath($path);
                }
            }
        }
        return $allPath;
    }

    /**
     *
     * @param array | string $path
     * @return array | string
     */
    private function normalizePath($path)
    {
        $newPath = null;
        if (is_array($path)) {
            foreach ($path as $value) {
                if (strpos($value, '?') === false) {
                    $newPath[] = FileHelper::normalizePath(str_replace(['.php', '.PHP'], '', $value));
                } else {
                    $newPath[] = FileHelper::normalizePath(str_replace(['.php', '.PHP'], '',
                                substr($value, 0, strpos($value, '?'))));
                }
            }
        } else {
            if (strpos($path, '?') === false) {
                $newPath = FileHelper::normalizePath(str_replace(['.php', '.PHP'], '', $path));
            } else {
                $newPath = FileHelper::normalizePath(str_replace(['.php', '.PHP'], '',
                            substr($path, 0, strpos($path, '?'))));
            }
        }
        return $newPath;
    }

    /**
     * @param \yii\base\Event $event
     */
    public function afterInit($event)
    {
        $this->loadTranslation(\Yii::$app->language);
    }

    /**
     * 
     */
    public function translateOriginalValues()
    {
        $module              = \Yii::$app->getModule('translation');
        $blackListAttributes = array_merge($this->blackListAttributes, $this->systemBlackListAttributes);
        $language            = $this->getLanguage();
        foreach ($this->getTranslation($language)->attributes as $key => $attribute) {
            $originalAttributes = $this->owner->attributes;
            $beta_language      = false;
            if(\Yii::$app instanceof \yii\web\Application)
            {    
                $beta_language      = \Yii::$app->user->can('CONTENT_TRANSLATOR') ? true : false;
            }
            $allLanguage        = $module->getAvailableLanguages($beta_language);
            if (!empty($attribute) && array_key_exists($key, $originalAttributes) && in_array($key,
                    $this->translationAttributes) && !in_array($key, $blackListAttributes)) {
                $this->owner->$key = $attribute;
            } else if (!empty($module->defaultTranslationLanguage) && empty($attribute) && array_key_exists($key,
                    $originalAttributes) && in_array($key, $this->translationAttributes) && !in_array($key,
                    $blackListAttributes) && (empty($module->defaultLanguage) || (strcasecmp($module->defaultLanguage,
                    $language) != 0))) {
                $defaultTranslationAttributes = $this->getTranslation($module->defaultTranslationLanguage);
                if (!empty($defaultTranslationAttributes) && !empty($defaultTranslationAttributes->$key)) {
                    $this->owner->$key = $defaultTranslationAttributes->$key;
                }
            }
        }
    }

    /**
     * @param \yii\base\Event $event
     */
    public function afterInsert($event)
    {
        $this->saveTranslation();
    }

    /**
     * @param \yii\base\Event $event
     */
    public function afterUpdate($event)
    {
        $this->saveTranslation();
    }

    /**
     * Sets current model's language
     *
     * @param $value
     */
    public function setLanguage($value)
    {
        $value = strtolower($value);
        if (!isset($this->_models[$value])) {
            $this->_models[$value] = $this->loadTranslation($value);
        }
        $this->_language = $value;
    }

    /**
     * Returns current models' language. If null, will return app's configured language.
     * @return string
     */
    public function getLanguage()
    {
        if ($this->_language === null) {
            if (empty($this->defaultLanguage)) {
                $this->_language = \Yii::$app->language;
            } else {
                $this->_language = $this->defaultLanguage;
            }
        }
        return $this->_language;
    }

    /**
     * Saves current translation model
     * @param type $save
     * @return boolean
     */
    public function saveTranslation($save = true)
    {
        $delete          = $this->owner->deleted_by;
        $languages       = [];
        $module          = \Yii::$app->getModule('translation');
        $result          = true;
        $languages       = \open20\amos\translation\models\TranslationConf::getStaticAllActiveLanguages($module->byPassPermissionInlineTranslation)->asArray()->all();
        $defaultLanguage = (!empty($this->defaultLanguage) ? $this->defaultLanguage : null);
        if ($delete) {
            if ($this->afterDeleteSource == self::DELETE_ONLY_LANGUAGE_SELECTED) {
                $languages                  = [];
                $languages[]['language_id'] = \Yii::$app->language;
            }
        }
        $classOwner = StringHelper::basename(get_class($this->owner));
        $classTrans = $module->modelNs.'\\'.$classOwner.'Translation';
        //If the translation model does not exist it will be generated
        $this->generateModels($classTrans);

        $tableOwner = $this->owner->tableName();
        $idOwner    = $this->owner->id;
        $pkTrans    = $tableOwner.'_id';
        $pkSource   = \Yii::$app->{$module->dbSource}->getTableSchema($this->owner->tableName())->primaryKey;
        foreach ($languages as $lang) {
            $language = $lang['language_id'];
            $model    = $this->getTranslation($language, $save);
            $model    = $this->loadAttributes($model);
            $dirty    = $model->getDirtyAttributes();
            if (empty($dirty) && !$delete) {
                return true; // we do not need to save anything
            }
            try {
                if (!method_exists(get_class($this->owner), $this->relation)) {
                    $notExistTranslation = true;
                } else {
                    /** @var \yii\db\ActiveQuery $relation */
                    $relation = $this->owner->getRelation($this->relation);
                }
            } catch (\Exception $ex) {
                $notExistTranslation = true;
            }
            if ($notExistTranslation) {
                $relation = $this->owner->hasMany($classTrans::className(), [$pkTrans => $pkSource[0]]);
            }
            $model->{key($relation->link)} = $this->owner->getPrimaryKey();
            $model->{$this->languageField} = $language;
            if ($this->enableWorkflow) {
                $dBehaavior = $model->detachBehavior($this->workflowBehavior);
                if (!$dBehaavior) {
                    $key       = $this->workflowBehavior;
                    $behaviors = $model->getBehaviors();
                    foreach ($behaviors as $k => $v) {
                        if (strpos($v->className(), $this->workflowBehavior) !== false) {
                            $key = $k;
                        }
                    }
                    $model->detachBehavior($key);
                }
                $model->status = $this->statusWorkflowApproved;
            }
            if ($this->enableValidationAttributes && !$delete) {
                $this->rollbackSource($model, $dirty, $defaultLanguage);
                try {
                    $model->setIsNewRecord(true);
                    $result = $result && $model->save();
                } catch (\Exception $ex1) {
                    $model->setIsNewRecord(false);
                    $pkModel = \Yii::$app->{$module->dbSource}->getTableSchema($model->tableName())->primaryKey;
                    foreach ($dirty as $k => $v) {
                        if (!in_array($k, $pkModel)) {
                            $model->setOldAttribute($k, '####update_id####');
                            $this->owner->setOldAttribute($k, '####update_id####');
                        }
                    }
                    $result = $result && $model->save();
                }
            } else if ($delete) {
                $ret = true;
                try {
                    $model->forceDelete();
                } catch (\Exception $ex) {
                    //do-nothing
                }
                $result = $result && $ret;
            } else {
                $this->rollbackSource($model, $dirty, $defaultLanguage);
                if (\Yii::$app->language == $language) {
                    // pr($language,'language');
                    try {
                        $model->setIsNewRecord(true);
                        $result = $result && $model->save(false);
                    } catch (\Exception $ex1) {
                        $model->setIsNewRecord(false);
                        $pkModel = \Yii::$app->{$module->dbSource}->getTableSchema($model->tableName())->primaryKey;
                        foreach ($dirty as $k => $v) {
                            if (!in_array($k, $pkModel)) {
                                $model->setOldAttribute($k, '####update_id####');
                                $this->owner->setOldAttribute($k, '####update_id####');
                            }
                        }
                        $result = $result && $model->save(false);
                    }
                }
            }
        }
        $this->clearCache();
        return $result;
    }

    /**
     * If the translation model does not exist it will be generated
     * @param string $classTrans
     */
    private function generateModels($classTrans)
    {
        $module = \Yii::$app->getModule('translation');
        if (class_exists($classTrans) == false) {
            $module->generateTranslationTables(false, true);
            $module->generateTranslationModels(true);
        }
    }

    /**
     * Rollback the record source
     * @param \yii\db\ActiveRecord $model
     * @param array $dirty
     * @param string $defaultLanguage
     */
    private function rollbackSource($model, $dirty, $defaultLanguage = null)
    {
        if (\Yii::$app->language != $defaultLanguage || $defaultLanguage == null) {
            if (!empty($dirty) && !is_null($this->owner)) {
                $module     = \Yii::$app->getModule('translation');
                $classOwner = get_class($this->owner);
                $idOwner    = $this->owner->id;
                $oldModel   = $classOwner::findOne(['id' => $idOwner]);
                $oldModel->detachBehaviors();
                $pkModel    = \Yii::$app->{$module->dbSource}->getTableSchema($model->tableName())->primaryKey;

                // Sometimes oldAttributes is not setted!
                if (($model->isNewRecord) && (count($model->oldAttributes) == 0)) {
                    $model->oldAttributes = $model->attributes;
                }

                foreach ($dirty as $k => $v) {
                    if (!in_array($k, $pkModel)) {
                        $oldModel->{$k} = $model->oldAttributes[$k];
                    }
                }

                $oldModel->save(false);
            }
        }
    }

    /**
     * Clear the cache
     */
    protected function clearCache()
    {
        $module = \Yii::$app->getModule('translation');
        if ($module->cached) {
            CachedActiveQuery::reset($module->queryCache);
        }
    }

    /**
     * Load attributes
     * @param ActiveRecord $model
     * @return ActiveRecord
     */
    private function loadAttributes($model)
    {
        $blackListAttributes = array_merge($this->blackListAttributes, $this->systemBlackListAttributes);
        foreach ($this->owner->toArray() as $key => $value) {
            if (in_array($key, $this->translationAttributes) && !in_array($key, $blackListAttributes)) {
                $model->{$key} = $value;
            }
        }

        return $model;
    }

    /**
     * Returns a related translation model
     *
     * @param string|null $language the language to return. If null, current sys language
     *
     * @return ActiveRecord
     */
    public function getTranslation($language = null, $save = false)
    {
        if ($language === null) {
            $language = $this->getLanguage();
        }

        if (!isset($this->_models[$language]) || $save) {
            $this->_models[$language] = $this->loadTranslation($language, $save);
        }

        return $this->_models[$language];
    }

    /**
     * Loads all specified languages. For example:
     *
     * ```
     * $model->loadTranslations("en-US");
     *
     * $model->loadTranslations(["en-US", "es-ES"]);
     *
     * ```
     *
     * @param string|array $languages
     */
    public function loadTranslations($languages)
    {
        $languages = (array) $languages;

        foreach ($languages as $language) {
            $this->loadTranslation($language);
        }
    }

    /**
     * Loads a specific translation model
     *
     * @param string $language the language to return
     *
     * @return null|\yii\db\ActiveQuery|static
     */
    private function loadTranslation($language, $save = false)
    {
        $translation         = null;
        $notExistTranslation = false;
        $module              = \Yii::$app->getModule('translation');

        try {
            /** @var \yii\db\ActiveQuery $relation */
            $relation = $this->owner->getRelation($this->relation);
            if ($module->cached) {
                $relation = CachedActiveQuery::instance($relation);
                $relation->cache($module->cacheDuration, $module->queryCache);
            }
        } catch (\Exception $ex) {
            $notExistTranslation = true;
        }
        if ($notExistTranslation) {
            $classOwner = StringHelper::basename(get_class($this->owner));
            $classTrans = $module->modelNs.'\\'.$classOwner.'Translation';
            //If the translation model does not exist it will be generated
            $this->generateModels($classTrans);
            $pkTrans    = $this->owner->tableName().'_id';
            $pkSource   = \Yii::$app->{$module->dbSource}->getTableSchema($this->owner->tableName())->primaryKey;
            $relation   = $this->owner->hasMany($classTrans::className(), [$pkTrans => $pkSource[0]]);
        }
        /** @var ActiveRecord $class */
        $class = $relation->modelClass;
        if ($this->owner->getPrimarykey()) {
            if ($this->enableWorkflow == true && !$save) {
                $translation = $class::find()->andWhere(
                    [$this->languageField => $language, key($relation->link) => $this->owner->getPrimarykey(), 'status' => $this->statusWorkflowApproved]
                );
                if ($module->cached) {
                    $translation = CachedActiveQuery::instance($translation);
                    $translation->cache($module->cacheDuration, $module->queryCache);
                }
                $translation = $translation->one();
            } else {
                $translation = $class::find()->andWhere(
                    [$this->languageField => $language, key($relation->link) => $this->owner->getPrimarykey()]
                );
                if ($module->cached) {
                    $translation = CachedActiveQuery::instance($translation);
                    $translation->cache($module->cacheDuration, $module->queryCache);
                }
                $translation = $translation->one();
            }
        }
        if ($translation === null) {
            $translation                         = new $class;
            $translation->{key($relation->link)} = $this->owner->getPrimaryKey();
            $translation->{$this->languageField} = $language;
        }

        return $translation;
    }

    /**
     * Populates already loaded translations
     */
    private function populateTranslations()
    {
        //translations
        $aRelated = $this->owner->getRelatedRecords();
        if (isset($aRelated[$this->relation]) && $aRelated[$this->relation] != null) {
            if (is_array($aRelated[$this->relation])) {
                foreach ($aRelated[$this->relation] as $model) {
                    $this->_models[$model->getAttribute($this->languageField)] = $model;
                }
            } else {
                $model                                                     = $aRelated[$this->relation];
                $this->_models[$model->getAttribute($this->languageField)] = $model;
            }
        }
    }

    /**
     * Return array of languages
     * @param string $table
     * @return array
     */
    protected function getActiveLanguages()
    {
        try {
            $translateManager = new \lajax\translatemanager\models\Language();
            $table            = $translateManager->getTableSchema()->name;
            $arrayLang        = [];

            if (Yii::$app->db->schema->getTableSchema($table, true) != null) {
                $arrayLang = (new \yii\db\Query())->from($table)->andWhere(['status' => 1])->select(['language_id', 'name'])->all();
            }
            return $arrayLang;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     *
     */
    protected function getModelTranslation()
    {
        $module = 'Traduzioni';
        $models = (!empty($module->translationBootstrap['configuration']['translationContents']['models']) ? $module->translationBootstrap['configuration']['translationContents']['models']
                : []);

        foreach ($models as $model) {
            if (!empty($model['namespace']) && !empty($model['attributes'])) {
                if (get_class($this->owner) == $model['namespace']) {
                    $configuration = $this->setTranslationContentsConfiguration($model, $event->sender);
                    $event->sender->attachBehavior('translationContents', $configuration);
                }
            }
        }
    }
}