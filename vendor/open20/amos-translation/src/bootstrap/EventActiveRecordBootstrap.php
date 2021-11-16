<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation
 * @category   CategoryName
 */

namespace open20\amos\translation\bootstrap;

use open20\amos\translation\AmosTranslation;
use open20\amos\translation\models\LanguageTranslateUserFields;
use Yii;
use yii\base\Event;
use yii\db\ActiveRecord;

/**
 * Class EventActiveRecordBootstrap
 * @package open20\amos\translation\bootstrap
 */
class EventActiveRecordBootstrap extends \yii\db\ActiveRecord
{
    private $configuration;

    /**
     * Application-specific roles initialization
     * @uses onApplicationAction
     */
    public function init()
    {
        $this->configuration = \Yii::$app->getModule('translation')->translationBootstrap;

        \yii\base\Event::on(\yii\db\ActiveRecord::className(), self::EVENT_INIT, [$this, 'onEventInit']);

        /** @var AmosTranslation $translationModule */
        $translationModule = Yii::$app->getModule(AmosTranslation::getModuleName());
        Event::on($translationModule->modelOwnerPlatformTranslation, ActiveRecord::EVENT_BEFORE_UPDATE,
            [$this, 'setTranslationOwner']);

        parent::init();
    }

    /**
     *
     * @param yii\base\Event $event
     */
    public function onEventInit($event)
    {
        $this->initConfigurationLabels();
        $this->enableTranslationContents($event);
        $this->enableTranslationLabels($event);
    }

    /**
     * @param yii\base\Event $event
     */
    public function setTranslationOwner($event)
    {
        if (Yii::$app instanceof \yii\web\Application && !Yii::$app->user->isGuest && !empty($event->sender->dirtyAttributes)) {
            /** @var AmosTranslation $translationModule */
            $translationModule = Yii::$app->getModule(AmosTranslation::getModuleName());
            $now               = date('Y-m-d H:i:s');
            $loggedUserId      = Yii::$app->user->id;
            $ltUserFields      = LanguageTranslateUserFields::findOne([
                    'language_translate_id' => $event->sender->{$translationModule->modelOwnerPlatformTrIdField},
                    'language_translate_language' => $event->sender->{$translationModule->modelOwnerPlatformTrLanguageField}
            ]);
            if (is_null($ltUserFields)) {
                $ltUserFields                              = new LanguageTranslateUserFields();
                $ltUserFields->language_translate_id       = $event->sender->{$translationModule->modelOwnerPlatformTrIdField};
                $ltUserFields->language_translate_language = $event->sender->{$translationModule->modelOwnerPlatformTrLanguageField};
                $ltUserFields->created_at                  = $now;
                $ltUserFields->created_by                  = $loggedUserId;
            }
            $ltUserFields->updated_at = $now;
            $ltUserFields->updated_by = $loggedUserId;
            $ltUserFields->save(YII_DEBUG);
        }
    }

    public function initConfigurationLabels()
    {
        $processed         = [];
        $translationModule = \Yii::$app->getModule('translation');
        $path              = \Yii::getAlias('@'.str_replace('\\', '/', $translationModule->modelNs)).'/'."{$translationModule->fileNameDbConfFields}".'.php';

        if(!is_writable($path)) {
            throw new \Exception("La Directory {$path} non esiste o non è scrivibile, bisogna correggere manualmente il problema");
        }

        try {

            if (!file_exists($path)) {
                $handle       = fopen($path, 'ab');
                $placeholder  = "/**************placeholder**************/";
                fwrite($handle, "<?php return [\n$placeholder\n];\n");
                fclose($handle);
                $propertyName = $translationModule->propertyModule;
                $conf         = "";
                foreach (\Yii::$app->getModules() as $k => $v) {
                    $module = \Yii::$app->getModule($k);
                    if (!empty($module) && !in_array($k, $translationModule->moduleBlackListForDbConfFields) && property_exists(get_class($module),
                            $propertyName)) {
                        if (!empty($module->{$propertyName}) && is_array($module->{$propertyName})) {
                            if (!in_array($k, $processed)) {
                                $processed[] = $k;
                                foreach ($module->db_fields_translation as $key => $value) {
                                    $conf .= "\t[\n";
                                    foreach ($value as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $v2   = implode("','", $v1);
                                            $conf .= "\t\t'".$k1."' => ['".$v2."'],\n";
                                        } else {
                                            $conf .= "\t\t'".$k1."' => '".$v1."',\n";
                                        }
                                    }
                                    $conf .= "\t],\n";
                                }
                            }
                        }
                    }
                }
                $fileContent   = file_get_contents($path);
                $configuration = str_replace($placeholder, $conf, $fileContent);
                $handle2       = fopen($path, 'wb');
                fwrite($handle2, $configuration);
                fclose($handle2);
            }
        } catch (\Exception $e) {
            \Yii::trace("Error in the configuration of the db fields translation", __METHOD__);
        }
    }

    /**
     *
     * @param yii\base\Event $event
     */
    public function enableTranslationLabels($event)
    {
        if (!empty($this->configuration['configuration']['translationLabels'])) {
            $translationLabels = $this->configuration['configuration']['translationLabels'];

            if (!empty($translationLabels['classBehavior']) && !empty($translationLabels['models']) && !empty(\Yii::$app->getModule('translatemanager'))) {
                foreach ($translationLabels['models'] as $model) {
                    if (!empty($model['namespace']) && !empty($model['attributes'])) {
                        $classSender = get_class($event->sender);
                        if ($classSender == $model['namespace']) {

                            $event->sender->attachBehavior('translationLabels',
                                [
                                'class' => (isset($model['classBehavior']) ? $model['classBehavior'] : $translationLabels['classBehavior']),
                                'translateAttributes' => $model['attributes'],
                                'category' => (!empty($model['category']) ? $model['category'] : (null != ($event->sender->tableName())
                                            ? $event->sender->tableName() : 'database')),
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     *
     * @param yii\base\Event $event
     */
    private function enableTranslationContents($event)
    {
        if (!empty($this->configuration['configuration']['translationContents'])) {
            $translationContents = $this->configuration['configuration']['translationContents'];
            if (!empty($translationContents['classBehavior']) && !empty($translationContents['models']) && !empty(\Yii::$app->getModule('translation'))) {
                foreach ($translationContents['models'] as $model) {
                    if (!empty($model['namespace']) && !empty($model['attributes'])) {
                        $classSender = get_class($event->sender);

                        if ($classSender == $model['namespace']) {
                            $configuration = $this->setTranslationContentsConfiguration($model, $event->sender);
                            $event->sender->attachBehavior('translationContents', $configuration);
                        }
                    }
                }
            }
        }
    }

    /**
     *
     * @param array $model
     * @return array
     */
    private function setTranslationContentsConfiguration($model, $sender)
    {
        $configuration = [];
        try {
            $module                                 = \Yii::$app->getModule('translation');
            $configuration['class']                 = (isset($model['classBehavior']) ? $model['classBehavior'] : $this->configuration['configuration']['translationContents']['classBehavior']);
            $configuration['translationAttributes'] = (!empty($model['attributes']) ? $model['attributes'] : $this->getAllAttributes($sender));
            $configuration['defaultLanguage']       = (!empty($model['defaultLanguage']) ? $model['defaultLanguage'] : $module->defaultLanguage);
            $configuration['forceTranslation']      = (!empty($model['forceTranslation']) ? $model['forceTranslation'] : $module->forceTranslation);
            $configuration['pathsTranslation']      = (!empty($model['pathsTranslation']) ? $model['pathsTranslation'] : $module->pathsForceTranslation);
            $enableWorkflow                         = $module->enableWorkflow;
            if (isset($model['enableWorkflow'])) {
                $enableWorkflow = boolval($model['enableWorkflow']);
            }
            $workflowBehavior       = (isset($model['workflowBehavior']) ? $model['workflowBehavior'] : $module->workflowBehavior);
            $workflowStatusApproved = (isset($model['statusWorkflowApproved']) ? $model['statusWorkflowApproved'] : $module->statusWorkflowApproved);
            $statusWorkflowInitial  = (isset($model['statusWorkflowInitial']) ? $model['statusWorkflowInitial'] : $module->statusWorkflowInitial);
            if ($enableWorkflow && !empty($workflowBehavior)) {
                $configuration['enableWorkflow']         = $enableWorkflow;
                $configuration['workflowBehavior']       = $workflowBehavior;
                $configuration['statusWorkflowApproved'] = $workflowStatusApproved;
                $configuration['statusWorkflowInitial']  = $statusWorkflowInitial;
                $configuration['workflow']               = (!empty($model['workflow']) ? $model['workflow'] : 'AmosTranslationWorkflow');
            }
            return $configuration;
        } catch (\Exception $ex) {
            \Yii::$app->getSession()->addFlash('danger',
                Yii::t('amostranslation', 'Configuration of the plugin Translation is not set correctly.'));
            if (YII_ENV_DEV) {
                pr($ex->getMessage());
                die;
            }
            return $configuration;
        }
    }

    protected function getAllAttributes($sender)
    {
        $attributes                       = [];
        $classname                        = get_class($sender);
        $table                            = $classname::tableName();
        $dbSource                         = \Yii::$app->getModule('translation')->dbSource;
        $defaultTypeAttributesToTranslate = \Yii::$app->getModule('translation')->defaultTypeAttributesToTranslate;
        $tableSchema                      = \Yii::$app->$dbSource->getTableSchema($table);
        if (!empty($defaultTypeAttributesToTranslate)) {
            foreach ((array) $tableSchema->columns as $key => $value) {
                if (in_array($value->type, $defaultTypeAttributesToTranslate)) {
                    $attributes[] = $value->name;
                }
            }
        }
        return $attributes;
    }
}