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

use Yii;
use yii\base\Application;
use yii\helpers\FileHelper;
use open20\amos\translation\models\TranslationConf;
use open20\amos\translation\AmosTranslation;

/**
 * Class TranslationBootstrap: <br />
 * Add behaviours for the translation of the model <br />
 * ELIGIBLE FOR DEPRECATION (DEPRECATED)
 * @package backend\bootstrap
 */
class EventViewBootstrap extends \yii\web\View
{

    /**
     * Application-specific roles initialization
     * @uses onApplicationAction
     */
    public function init()
    {
        \yii\base\Event::on(\yii\web\View::className(), self::EVENT_AFTER_RENDER, [$this, 'onEventAfterRender']);
        //\yii\base\Event::on(\yii\web\View::className(), self::EVENT_END_BODY, [$this, 'onEventAfterRender']);
        parent::init();
    }

    /**
     * 
     * @param yii\base\Event $event
     */
    public function onEventAfterRender($event)
    {
        $moduleTranslation = \Yii::$app->getModule('translation');
        if (!empty($moduleTranslation)) {
            if ($moduleTranslation->enableWidgetView && \Yii::$app instanceof Application) {
                $viewSender    = FileHelper::normalizePath(str_replace(['.php', '.PHP'], '', $event->sender->viewFile));
                $modelSource   = null;
                $modelsEnabled = [];

                $blackListModels = array_merge([
                    'open20\amos\translation\bootstrap\EventViewBootstrap',
                    ], $moduleTranslation->eventViewBlackListModels);

                $blackListViews = array_merge([
                    'views/layouts/',
                    'yii2-debug\views',
                    ], $moduleTranslation->eventViewBlackListViews);


                $checkView = false;
                $checkPart = false;

                foreach ($blackListViews as $val) {
                    if (strpos($event->sender->viewFile, $val) === false) {
                        $checkView = true;
                    }
                }

                foreach ($moduleTranslation->eventViewWhiteListParts as $part) {
                    if (in_array($part, explode(DIRECTORY_SEPARATOR, str_replace('.php', '', $event->sender->viewFile)))) {
                        $checkPart = true;
                    }
                }
                $arrayModels = [];
                if ($checkView && $checkPart) {
                    if (isset($moduleTranslation->translationBootstrap['configuration']['translationContents']['models'])) {
                        foreach ($moduleTranslation->translationBootstrap['configuration']['translationContents']['models'] as $value) {
                            if (!empty($value['namespace'])) {
                                $arrayModels[$value['namespace']] = (isset($value['workflow']) ? $value['workflow'] : 'AmosTranslationWorkflow');
                            }
                            if (!empty($value['view']) && !empty($value['namespace']) && FileHelper::normalizePath(str_replace([
                                    '.php', '.PHP'], '', \Yii::getAlias($value['view']))) == $viewSender) {
                                $modelSource = $value['namespace'];
                            }
                        }
                    }
                    if (empty($modelSource)) {
                        if (!empty($event->sender->context->model) && !in_array(get_class($event->sender->context->model),
                                $blackListModels) && array_key_exists(get_class($event->sender->context->model),
                                $arrayModels)) {
                            $modelSource = $event->sender->context->model;
                        }
                    }
                    if (!empty($modelSource)) {
                        $conf      = new TranslationConf();
                        $statuses  = [];
                        $languages = TranslationConf::getStaticAllActiveLanguages(true);
                        $namespace = get_class($modelSource);
                        $workflow  = $arrayModels[$namespace];
                        if ($languages->count() > 1) {
                            foreach ($languages->all() as $lang) {
                                $statusLang = $conf->getStatus($namespace, $lang, $modelSource->id);
                                $label      = AmosTranslation::t('amostranslation', 'Not translated');
                                if (!empty($statusLang->status)) {
                                    $label = (!empty($statusLang->status) ? $conf->getLabelStatus($statusLang->status,
                                            $workflow) : AmosTranslation::t('amostranslation', 'Not translated'));
                                }
                                $statuses[$lang->language_id] = $label;
                            }
                            echo $event->sender->renderFile($moduleTranslation->widgetViewFile,
                                ['statusLangs' => $statuses, 'namespace' => $namespace, 'getParams' => \Yii::$app->request->get()]);
                        }
                    }
                }
            }
        }
    }
}