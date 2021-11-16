<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\widgets
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\widgets;

use open20\amos\core\models\ModelsClassname;
use open20\amos\notificationmanager\models\NotificationContentLanguage;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class NotifyContentLanguageWidget
 * @package open20\amos\notificationmanager\widgets
 */
class NotifyContentLanguageWidget extends Widget
{
    public $model;
    public $class = 'form-group col-xs-12';
    public $id = 'notify-content-language-widget';
    
    private $defaultLanguage;
    private $value;
    private $notificationContentLanguage = null;
    private $module;
    
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        
        if (empty($this->model)) {
            throw new InvalidConfigException('The param $model is mandatory');
        }
        
        $modelclassname = ModelsClassname::find()
            ->andWhere(['classname' => get_class($this->model)])->one();
        
        if (empty($modelclassname)) {
            throw new InvalidConfigException('You have to configure the class ' . get_class($this->model) . ' in the table "models_classname"');
        }
        
        $module = \Yii::$app->getModule('notify');
        if ($module) {
            $this->module = $module;
        }
        
        $this->defaultLanguage = \Yii::$app->language;
        $language = $this->getNotifyContentLanguage();
        if (empty($language)) {
            $this->value = $this->defaultLanguage;
        } else {
            $this->value = $language;
        }
    }
    
    /**
     * @return string
     */
    public function run()
    {
        if ($this->module->enableNotificationContentLanguage) {
            return $this->render('notify_content_language', [
                'model' => $this->model,
                'value' => $this->value,
                'widget' => $this,
                'module' => $this->module
            ]);
        } else {
            return '';
        }
    }
    
    /**
     * @return null
     */
    public function getNotifyContentLanguage()
    {
        if (!$this->model->isNewRecord) {
            $classModel = get_class($this->model);
            $modelclassname = ModelsClassname::find()->andWhere(['classname' => $classModel])->one();
            if ($modelclassname) {
                $notificationContentLanguage = NotificationContentLanguage::find()
                    ->andWhere(['models_classname_id' => $modelclassname->id])
                    ->andWhere(['record_id' => $this->model->id])->one();
                $this->notificationContentLanguage = $notificationContentLanguage;
                if ($notificationContentLanguage) {
                    return $notificationContentLanguage->language;
                }
            }
        }
        return null;
    }
}
