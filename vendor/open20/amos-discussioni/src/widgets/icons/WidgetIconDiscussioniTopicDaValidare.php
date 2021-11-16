<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;

use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\discussioni\models\search\DiscussioniTopicSearch;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Application as Web;

/**
 * Class WidgetIconDiscussioniTopicDaValidare
 * This widget can appear on dashboard. This class is used for creation and general configuration.
 * widget that link to the discussion topic, list of threads that are to be validated
 *
 * @package open20\amos\discussioni\widgets\icons
 */
class WidgetIconDiscussioniTopicDaValidare extends WidgetIcon
{

    /**
     * Init of the class, set of general configurations
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-primary'
        ];

        $this->setLabel(AmosDiscussioni::tHtml('amosdiscussioni', 'Discussioni da validare'));
        $this->setDescription(AmosDiscussioni::t('amosdiscussioni', 'Elenco delle discussioni che sono da vallidare'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('disc');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('comment');
        }

        $this->setUrl(['/discussioni/discussioni-topic/to-validate-discussions']);
        $this->setCode('DISCUSSIONI_TOPIC_DA_VALIDARE');
        $this->setModuleName('discussioni');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

    }

    /**
     * all widgets added to the container object retrieved from the module controller
     *
     * @return array
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => $this->getWidgetsIcon()]
        );
    }

    /**
     * TEMPORARY
     */
    public function getWidgetsIcon()
    {
        $widgets = [];

        $WidgetIconDiscussioniTopic = new WidgetIconDiscussioniTopic();
        if ($WidgetIconDiscussioniTopic->isVisible()) {
            $widgets[] = $WidgetIconDiscussioniTopic->getOptions();
        }

        return $widgets;
    }

}
