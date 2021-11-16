<?php

namespace open20\amos\translation\models;

use Yii;
use open20\amos\translation\AmosTranslation;
use yii\helpers\StringHelper;
use open20\amos\core\icons\AmosIcons;

/**
 * This is the base-model class for table "translation_conf".
 *
 * @property string $namespace
 * @property string $plugin
 * @property integer $model_generated
 * @property string $fields
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class TranslationConf extends \open20\amos\core\record\Record
{
    public $id_translate;
    public $language_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'translation_conf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['namespace'], 'required'],
            [['fields'], 'string'],
            [['id_translate', 'language_id'], 'safe'],
            [['model_generated'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['namespace', 'plugin'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'namespace' => AmosTranslation::t('amostranslation', 'Namespace'),
            'plugin' => AmosTranslation::t('amostranslation', 'Plugin'),
            'model_generated' => AmosTranslation::t('amostranslation', 'Model generated'),
            'id_translate' => AmosTranslation::t('amostranslation', 'Namespace'),
            'language_id' => AmosTranslation::t('amostranslation', 'Language'),
            'fields' => AmosTranslation::t('amostranslation', 'Fields'),
            'created_at' => AmosTranslation::t('amostranslation', 'Creato il'),
            'updated_at' => AmosTranslation::t('amostranslation', 'Aggiornato il'),
            'deleted_at' => AmosTranslation::t('amostranslation', 'Cancellato il'),
            'created_by' => AmosTranslation::t('amostranslation', 'Creato da'),
            'updated_by' => AmosTranslation::t('amostranslation', 'Aggiornato da'),
            'deleted_by' => AmosTranslation::t('amostranslation', 'Cancellato da'),
        ];
    }

    public static function getTranslateContents($params = null)
    {
        $newExpression = new \yii\db\Expression("concat_ws('_', translation_conf.namespace, language.language_id) as id_translate");

        $activeQuery = (new \yii\db\Query())->from(\open20\amos\translation\models\TranslationConf::tableName())
            ->andWhere(['model_generated' => 1])
            ->join('cross join', \lajax\translatemanager\models\Language::tableName())
            ->andWhere(['status' => 1])
            ->select([$newExpression, 'translation_conf.namespace', 'language.language_id', 'translation_conf.plugin', 'language.language',
            'language.name']);
        if (!empty($params) && !empty($params['TranslationConf']) && is_array($params['TranslationConf'])) {
            $activeQuery->andFilterWhere($params['TranslationConf']);
        }
        $activeQuery->orderBy('plugin, namespace');
        return $activeQuery;
    }

    /**
     * 
     * @return \lajax\translatemanager\models\Language
     */
    public function getAllActiveLanguages()
    {
        if (\Yii::$app->authManager->checkAccess(\Yii::$app->getUser()->getId(), 'TRANSLATE_MANAGER')) {
            return \lajax\translatemanager\models\Language::find()->andWhere(['status' => 1]);
        } else {
            $allowedLanguageByUser = TranslationUserLanguageMm::find()->andWhere(['user_id' => \Yii::$app->getUser()->getId()])->select('language');
            $languages             = [];
            if ($allowedLanguageByUser->count() > 0) {
                foreach ((array) $allowedLanguageByUser->all() as $value) {
                    $languages[] = $value->language;
                }
            }
            return \lajax\translatemanager\models\Language::find()->andWhere(['status' => 1])->andWhere(['IN', 'language_id',
                    $languages]);
        }
    }

    /**
     * 
     * @param boolean $force
     * @param string $namespace
     * @param boolean $statu_beta Default true
     * @return \lajax\translatemanager\models\Language
     */
    public static function getStaticAllActiveLanguages($force = false, $namespace = null, $status_beta = true)
    {
        if ($force || \Yii::$app->authManager->checkAccess(\Yii::$app->getUser()->getId(), 'TRANSLATE_MANAGER')) {
            if ($status_beta === true && \Yii::$app->user->can('CONTENT_TRANSLATOR')) {
                $ret = \lajax\translatemanager\models\Language::find()->andWhere(['>=', 'status', 1]);
            } else {
                $ret = \lajax\translatemanager\models\Language::find()->andWhere(['status' => 1]);
            }
            if ($namespace) {
                $module         = \Yii::$app->getModule('translation');
                $classNameTrans = $module->modelNs.'\\'.StringHelper::basename($namespace)."Translation";
                $fieldLanguage  = (!empty($module->languageField) ? $module->languageField : 'language');
                $statusApproved = $module->statusWorkflowApproved;
                $baseNamespace  = StringHelper::basename($namespace);
                $namespaceTrans = "{$module->modelNs}\\{$baseNamespace}Translation";
                $approved       = $namespaceTrans::find()->andWhere(['status' => $statusApproved])->select("$fieldLanguage as language_id");
                $ret->andWhere(['IN', 'language_id', $approved]);
            }
            return $ret;
        } else {
            $allowedLanguageByUser = TranslationUserLanguageMm::find()->andWhere(['user_id' => \Yii::$app->getUser()->getId()])->select('language');
            $languages             = [];
            if ($allowedLanguageByUser->count() > 0) {
                foreach ((array) $allowedLanguageByUser->all() as $value) {
                    $languages[] = $value->language;
                }
            }
            $ret = \lajax\translatemanager\models\Language::find()->andWhere(['status' => 1])->andWhere(['IN', 'language_id',
                $languages]);
            if ($namespace) {
                $module         = \Yii::$app->getModule('translation');
                $classNameTrans = $module->modelNs.'\\'.StringHelper::basename($namespace)."Translation";
                $fieldLanguage  = (!empty($module->languageField) ? $module->languageField : 'language');
                $statusApproved = $module->statusWorkflowApproved;
                $approved       = $namespace::find()->andWhere(['status' => $statusApproved])->select("$fieldLanguage as language_id");
                $ret->andWhere(['IN', 'language_id', $approved]);
            }
            return $ret;
        }
    }

    public function getAllPlugins()
    {
        return $this->find()->andWhere(['model_generated' => 1]);
    }

    public function getProgress($namespace, $lang)
    {
        $bars  = [];
        $perc1 = null;
        $perc2 = null;

        $module         = \Yii::$app->getModule('translation');
        $classNameTrans = $module->modelNs.'\\'.StringHelper::basename($namespace)."Translation";
        $fieldLanguage  = (!empty($module->languageField) ? $module->languageField : 'language');
        $currentLang    = (!empty($module->defaultLanguage) ? $module->defaultLanguage : \Yii::$app->language);

        $source    = $namespace::find();
        $translate = $classNameTrans::find()->andWhere([$fieldLanguage => $lang]);

        if (!empty($module->enableWorkflow) && $module->enableWorkflow === true) {
            $totTranslate = $translate->count();
            $translate->andWhere(['status' => $module->statusWorkflowApproved]);
            $perc1        = ($source->count() ? round(bcdiv(bcmul($translate->count(), 100, 4), $source->count(), 4), 2)
                    : 0);
            $perc2        = ($source->count() ? round(bcdiv(bcmul(bcsub($totTranslate, $translate->count(), 4), 100, 4),
                        $source->count(), 4), 2) : 0);
        } else {
            $perc1 = round(bcdiv(bcmul($translate->count(), 100, 4), $source->count(), 4), 2);
        }
        if ($perc2 === null) {
            $bars['bars'][] = ['percent' => $perc1, 'label' => $perc1.'%', 'options' => ['class' => 'progress-bar-success']];
        } else {
            $bars['bars'][] = ['percent' => $perc1, 'label' => $perc1.'%', 'options' => ['class' => 'progress-bar-success']];
            $bars['bars'][] = ['percent' => $perc2, 'label' => $perc2.'%', 'options' => ['class' => 'progress-bar-warning']];
        }

        return $bars;
    }

    public function getStatus($namespace, $lang, $id)
    {
        $bars  = [];
        $perc1 = null;
        $perc2 = null;

        $module         = \Yii::$app->getModule('translation');
        $classNameTrans = $module->modelNs.'\\'.StringHelper::basename($namespace)."Translation";
        $fieldLanguage  = (!empty($module->languageField) ? $module->languageField : 'language');
        $pk             = \Yii::$app->{$module->dbTranslation}->getTableSchema($classNameTrans::tableName())->primaryKey;

        $translate = $classNameTrans::find()->andWhere([$pk[0] => $id, $fieldLanguage => $lang]);

        return $translate->one();
    }

    public static function getColumnsRecord($namespace, $lang)
    {
        $columns = [];
        $module  = \Yii::$app->getModule('translation');

        $models         = (!empty($module->translationBootstrap['configuration']['translationContents']['models']) ? $module->translationBootstrap['configuration']['translationContents']['models']
                : []);
        $attributes     = [];
        $enableWorkflow = false;

        \Yii::$app->getView()->params['namespaceTrans']  = $module->modelNs.'\\'.StringHelper::basename($namespace).'Translation';
        \Yii::$app->getView()->params['namespaceSource'] = $namespace;
        \Yii::$app->getView()->params['workflowTrans']   = 'AmosTranslationWorkflow';
        \Yii::$app->getView()->params['dbTrans']         = $module->dbTranslation;
        $namespaceTrans                                  = \Yii::$app->getView()->params['namespaceTrans'];
        $pk                                              = \Yii::$app->{$module->dbTranslation}->getTableSchema($namespaceTrans::tableName())->primaryKey;
        \Yii::$app->getView()->params['pkTrans']         = $pk;
        \Yii::$app->getView()->params['langTrans']       = $lang;

        foreach ((array) $models as $value) {
            if ($namespace == $value['namespace']) {
                $attributes = $value['attributes'];
                if (isset($value['workflow'])) {
                    \Yii::$app->getView()->params['workflowTrans'] = $value['workflow'];
                }
                if (isset($value['enableWorkflow'])) {
                    if ($value['enableWorkflow'] == true) {
                        $enableWorkflow = true;
                    }
                } else {
                    $enableWorkflow = $module->enableWorkflow;
                }
            }
        }

        if ($enableWorkflow == true) {
            $columns[] = [
                'attribute' => 'status',
                'value' => function ($model) {
                    $workflow = \Yii::$app->getView()->params['workflowTrans'];
                    $status   = str_replace($workflow.'/', '', $model['status']);
                    if (!empty($status)) {
                        $labelMd = (new \yii\db\Query())
                            ->from('sw_metadata')
                            ->where(['workflow_id' => $workflow, 'status_id' => $status, 'key' => 'label'])
                            ->select('value')
                            ->one();

                        $label = (!empty($labelMd['value']) ? $labelMd['value'] : $status);

                        return $label;
                    } else {
                        return AmosTranslation::t('amostranslation', 'Not set');
                    }
                }
            ];
        }

        $columns[] = [
            'attribute' => 'created_at',
            'format' => 'datetime',
        ];

        $columns[] = [
            'attribute' => 'created_by',
            'label' => AmosTranslation::t('amostranslation', 'Created by'),
            'format' => 'html',
            'value' => function ($model) {
                $value       = AmosTranslation::t('amostranslation', 'User #').$model['created_by'];
                $moduleAdmin = \Yii::$app->getModule('admin');
                if (!empty($moduleAdmin)) {
                    $userProfile        = \open20\amos\admin\AmosAdmin::instance()->createModel('UserProfile');
                    $record             = $userProfile::findOne(['user_id' => $model['created_by']]);
                    $nameUserProfile    = \Yii::$app->getModule('translation')->nameCreatedBy;
                    $surnameUserProfile = \Yii::$app->getModule('translation')->surnameCreatedBy;
                    if ($record && !empty($nameUserProfile) && !empty($surnameUserProfile)) {
                        $value .= (!empty($record->{$nameUserProfile}) ? ('<br>'.$record->{$nameUserProfile}) : '').(!empty($record->{$surnameUserProfile})
                                ? (' '.$record->{$surnameUserProfile}) : '');
                    }
                }
                return $value;
            }
        ];

        if (!empty($attributes)) {
            foreach ($attributes as $value) {
                $columns[] = [
                    'attribute' => $value,
                    'format' => 'html'
                ];
            }
        } else {
            $attributes = $module->getModelAttributes($namespace, true);
            $ind        = 0;
            if (!empty($attributes)) {
                foreach ($attributes as $value) {
                    if ($ind < $module->numberGridViewField) {
                        $columns[] = [
                            'attribute' => $value,
                            'format' => 'html'
                        ];
                        $ind++;
                    }
                }
            }
        }

        $columns[] = [
            'class' => \open20\amos\core\views\grid\ActionColumn::className(),
            'template' => '{custom}',
            'buttons' => [
                'custom' => function ($url, $model) {
                    $url       = \yii\helpers\Url::current();
                    $class     = \Yii::$app->getView()->params['namespaceTrans'];
                    $namespace = \Yii::$app->getView()->params['namespaceSource'];
                    $lang      = \Yii::$app->getView()->params['langTrans'];

                    $pk = \Yii::$app->{\Yii::$app->getView()->params['dbTrans']}->getTableSchema($class::tableName())->primaryKey;

                    return \yii\helpers\Html::a(AmosIcons::show('square-right', ['class' => 'btn btn-tool-secondary']),
                            \Yii::$app->urlManager->createUrl(['/translation/default/update', 'id' => $model[$pk[0]], 'lang' => $lang,
                                'namespace' => \Yii::$app->getView()->params['namespaceSource'], 'url' => $url]),
                            [
                            'title' => AmosTranslation::t('app', 'Detail'),
                            'model' => $model
                    ]);
                },
            ]
        ];

        return $columns;
    }

    public static function getTranslationWorkflow($namespace, $status_complete = false)
    {
        $module         = \Yii::$app->getModule('translation');
        $models         = (!empty($module->translationBootstrap['configuration']['translationContents']['models']) ? $module->translationBootstrap['configuration']['translationContents']['models']
                : []);
        $enableWorkflow = false;
        $workflow       = 'AmosTranslationWorkflow';
        $status         = "{$module->statusWorkflowInitial}";

        foreach ((array) $models as $value) {
            if ($namespace == $value['namespace']) {
                if (isset($value['workflow'])) {
                    $workflow = $value['workflow'];
                }
                if (isset($value['statusWorkflowInitial'])) {
                    $status = "{$value['statusWorkflowInitial']}";
                }
                if (isset($value['enableWorkflow'])) {
                    if ($value['enableWorkflow'] == true) {
                        $enableWorkflow = true;
                    }
                } else {
                    $enableWorkflow = $module->enableWorkflow;
                }
            }
        }
        if ($enableWorkflow) {
            if ($status_complete) {
                return $status;
            }
            return $workflow;
        }
        return null;
    }

    public static function getFieldsRecord($namespace)
    {
        $module         = \Yii::$app->getModule('translation');
        $models         = (!empty($module->translationBootstrap['configuration']['translationContents']['models']) ? $module->translationBootstrap['configuration']['translationContents']['models']
                : []);
        $enableWorkflow = false;
        $workflow       = 'AmosTranslationWorkflow';

        foreach ((array) $models as $value) {
            if ($namespace == $value['namespace']) {
                $attributes = $value['attributes'];
                if (isset($value['enableWorkflow'])) {
                    if ($value['enableWorkflow'] == true) {
                        $enableWorkflow = true;
                    }
                } else {
                    $enableWorkflow = $module->enableWorkflow;
                }
            }
        }

        if (empty($attributes)) {
            $attributes = $module->getModelAttributes($namespace, true);
        }
        if ($enableWorkflow == true) {
            $attributes[] = 'status';
        }
        return $attributes;
    }

    public static function getSource($get_lang = null, $id, $lang, $namespace)
    {
        $module         = \Yii::$app->getModule('translation');
        $classNameTrans = $module->modelNs.'\\'.StringHelper::basename($namespace)."Translation";

        $model    = new $classNameTrans;
        $pkSource = \Yii::$app->{$module->dbSource}->getTableSchema($namespace::tableName())->primaryKey;

        $attributes  = TranslationConf::getFieldsRecord($namespace, $lang);
        $tableSource = $namespace::tableName();
        $tableTrans  = $classNameTrans::tableName();

        $pk   = $model->getPrimaryKey();
        reset($pk);
        $pkId = key($pk);

        $selectsTrans  = [];
        $selectsSource = [];

        foreach ($attributes as $field) {
            $selectsTrans[] = "{$tableTrans}.{$field}";
            if ($field != 'status') {
                $selectsSource[] = "{$tableSource}.{$field}";
            }
        }
        $newExpSource = new \yii\db\Expression("$tableSource.{$pkSource[0]} as '$pkId'");
        $newExpTrans  = new \yii\db\Expression("$tableTrans.{$pkId} as '$pkId'");

        try {
            if ($get_lang) {
                $source = (new \yii\db\Query())->from($tableTrans)
                    ->andWhere(["{$pkId}" => $id])
                    ->andWhere(["$tableTrans.{$module->languageField}" => $get_lang])
                    ->select(array_merge([$newExpTrans], $selectsTrans));
                if ($source->count() != 1) {
                    $source   = (new \yii\db\Query())->from($tableSource)
                        ->andWhere(["{$pkSource[0]}" => $id])
                        ->andWhere(["$tableSource.deleted_by" => null])
                        ->select(array_merge([$newExpSource], $selectsSource));
                    $get_lang = null;
                }
            } else {
                $source = (new \yii\db\Query())->from($tableSource)
                    ->andWhere(["{$pkSource[0]}" => $id])
                    ->andWhere(["$tableSource.deleted_by" => null])
                    ->select(array_merge([$newExpSource], $selectsSource));
            }
        } catch (\Exception $ex) {
            $source = (new \yii\db\Query())->from($tableSource)
                ->andWhere(["{$pkSource[0]}" => $id])
                ->select(array_merge([$newExpSource], $selectsSource));
        }

        return [$get_lang, $source];
    }

    public static function setTranslation($id, $lang, $namespace)
    {
        $module         = \Yii::$app->getModule('translation');
        $classNameTrans = $module->modelNs.'\\'.StringHelper::basename($namespace)."Translation";

        $model = new $classNameTrans;

        $attributes = TranslationConf::getFieldsRecord($namespace, $lang);

        $tableTrans = $classNameTrans::tableName();

        $pk   = $model->getPrimaryKey();
        reset($pk);
        $pkId = key($pk);

        $queryTrans = (new \yii\db\Query())->from($tableTrans)
            ->andWhere([$pkId => $id])
            ->andWhere([$module->languageField => $lang]);
        if ($queryTrans->count() == 0) {
            $connection                     = \Yii::$app->{$module->dbTranslation};
            $values                         = [];
            $values[$pkId]                  = $id;
            $values[$module->languageField] = $lang;
            if (in_array('status', $attributes)) {
                $values['status'] = TranslationConf::getTranslationWorkflow($namespace, true);
            }

            $connection->createCommand()->insert($tableTrans, $values)->execute();
        }
    }

    public static function getFields($namespace, $lang)
    {
        $module           = \Yii::$app->getModule('translation');
        $classNameTrans   = $module->modelNs.'\\'.StringHelper::basename($namespace)."Translation";
        $language_source  = null;
        $querySource      = null;
        $attributes       = TranslationConf::getFieldsRecord($namespace, $lang);
        $model            = new $classNameTrans;
        $pkSource         = \Yii::$app->{$module->dbSource}->getTableSchema($namespace::tableName())->primaryKey;
        $tableSchemaTrans = \Yii::$app->{$module->dbTranslation}->getTableSchema($classNameTrans::tableName());

        $stringField = [];
        $textField   = [];
        foreach ($tableSchemaTrans->columns as $key => $val) {
            if (in_array($key, $attributes) && $key != 'status') {
                if ($val->type == 'string') {
                    $stringField[] = $key;
                }
                if ($val->type == 'text') {
                    $textField[] = $key;
                }
            }
        }
        return [$stringField, $textField];
    }

    public function getLabelStatus($state, $workflow)
    {
        $status = str_replace($workflow.'/', '', $state);
        if (!empty($status)) {
            $labelMd = (new \yii\db\Query())
                ->from('sw_metadata')
                ->where(['workflow_id' => $workflow, 'status_id' => $status, 'key' => 'label'])
                ->select('value')
                ->one();

            $label = (!empty($labelMd['value']) ? $labelMd['value'] : $status);

            return $label;
        } else {
            return AmosTranslation::t('amostranslation', 'Not translated');
        }
    }
}