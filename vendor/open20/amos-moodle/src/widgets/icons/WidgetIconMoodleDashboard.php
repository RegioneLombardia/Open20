<?php
namespace open20\amos\moodle\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconMoodleDashboard extends WidgetIcon {

    public function init() {
        parent::init();

        $this->setLabel(\Yii::t('open20\amos\moodle\widgets\icons' , 'E-Learning'));
        $this->setDescription(Yii::t('open20\amos\moodle\widgets\icons', 'Piattaforma di E-learning'));

        
        $this->setIconFramework('dash');
        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {           
            $this->setIcon('book');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('book');
        }

        $this->setUrl('/moodle');
        $this->setModuleName('moodle');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
        
         // Read and reset counter from bullet_counters table, bacthed calculated!
        if($this->disableBulletCounters == false) {
//            $widgetAllnews = \Yii::createObject(WidgetIconAllNews::className());
//            $this->setBulletCount(
//                $widgetAllnews->getBulletCount()
//            );
        }
    }

    public function getOptions() {
        $options = parent::getOptions();

        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => $this->getWidgetsIcon()]);
    }

    /**
    * Recupera i widget figli da far visualizzare nella dashboard secondaria
    * @return [open20\amos\core\widget\WidgetIcon] Array con i widget della dashboard
    */
    public function getWidgetsIcon() {
        $widgets = [];

        $widget = \open20\amos\dashboard\models\AmosWidgets::find()->andWhere(['module' => 'moodle'])->andWhere(['type' => 'ICON'])->andWhere(['!=', 'child_of', NULL])->all();

        foreach ($widget as $Widget) {
        $className = (strpos($Widget['classname'], '\\') === 0)? $Widget['classname'] : '\\' . $Widget['classname'];
        $widgetChild = new $className;
        if($widgetChild->isVisible()){
            $widgets[] = $widgetChild->getOptions();
        }
    }
    return $widgets;
    }

}