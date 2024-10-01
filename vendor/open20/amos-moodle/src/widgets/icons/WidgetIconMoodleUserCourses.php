<?php

namespace open20\amos\moodle\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconMoodleUserCourses extends WidgetIcon
{
    /**
     * 
     */
    public function init() {
        parent::init();

        $this->setLabel(\open20\amos\moodle\AmosMoodle::_tHtml('#own_courses'));
        $this->setDescription(\open20\amos\moodle\AmosMoodle::_tHtml('#own_courses'));

        $this->setIconFramework('dash');
        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIcon('book');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('feed');
        }

        $this->setUrl(['/moodle/course/own-courses']);
        $this->setModuleName('moodle');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getOptions() {
        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => []]
        );
    }

}
