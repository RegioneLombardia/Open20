<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\events\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;

use open20\amos\events\AmosEvents;
use open20\amos\events\models\search\EventSearch;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconAllNews
 * @package open20\amos\news\widgets\icons
 */
class WidgetIconMyEvents extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-primary'
        ];

        $this->setLabel(AmosEvents::tHtml('amosevents', 'I miei eventi'));
        $this->setDescription(AmosEvents::t('amosevents', 'Visualizza gli eventi a cui sei registrato'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('eventi');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('calendar');
        }
        $this->setUrl(['/events/event-dashboard/my-events']);
        $this->setCode('ALL-EVENTS');
        $this->setModuleName('events');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
  
//        if ($this->disableBulletCounters == false) {
//            /** @var AmosEvents $eventsModule */
//            $eventsModule = AmosEvents::instance();
//            /** @var EventSearch $search */
//            $search = $eventsModule->createModel('EventSearch');
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    $eventsModule->model('Event'),
//                    $search->searchAllEvents([])->query
//                )
//            );
//        }
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @inheritdoc
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => []]
        );
    }
    /**
     * @return bool
     */
    public function isVisible()
    {
        $module = \Yii::$app->getModule('events');
        if($module){
            if($module->enableNewWizard == true){
                return true;
            }
        }
        return false;
    }

}
