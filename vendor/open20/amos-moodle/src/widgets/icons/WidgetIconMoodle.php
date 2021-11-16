<?php
namespace open20\amos\moodle\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconMoodle extends WidgetIcon {

    public function init() {
        parent::init();

        $this->setLabel(\Yii::t('open20\amos\moodle\widgets\icons' , 'E-Learning'));
        $this->setDescription(Yii::t('open20\amos\moodle\widgets\icons', 'Piattaforma di E-learning'));

        $this->setIcon('book');
        $this->setIconFramework('dash');


        $this->setUrl(Yii::$app->urlManager->createUrl(['/moodle']));
        $this->setModuleName('moodle');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }

}