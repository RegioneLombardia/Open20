<?php
namespace open20\amos\moodle\widgets\icons;

use open20\amos\moodle\AmosMoodle;
use open20\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconMoodleLessons extends WidgetIcon {

    public function init() {
        parent::init();

       
        $this->setLabel(\Yii::t('open20\amos\moodle\widgets\icons' , 'Lezioni'));
        $this->setDescription(Yii::t('open20\amos\moodle\widgets\icons', 'Le lezioni del corso'));

        $this->setIcon('book');
        $this->setIconFramework('dash');

 //pr(Yii::$app->getUser()->can("MOODLE_ADMIN"),"user");
        $targetUrl = Yii::$app->urlManager->createUrl(['/moodle/topic/index']);
        if (Yii::$app->getUser()->can(AmosMoodle::MOODLE_ADMIN)) {
             $targetUrl = Yii::$app->urlManager->createUrl(['/moodle/administration/index']);
        }
        $this->setUrl($targetUrl);
        $this->setModuleName('moodle');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }

}