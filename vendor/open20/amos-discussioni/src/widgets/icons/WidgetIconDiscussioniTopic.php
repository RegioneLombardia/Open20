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
use open20\amos\utility\models\BulletCounters;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Application as Web;

/**
 * Class WidgetIconDiscussioniTopic
 * This widget can appear on dashboard. This class is used for creation and general configuration.
 * Widget that link to the discussion topic
 *
 * @package open20\amos\discussioni\widgets\icons
 */
class WidgetIconDiscussioniTopic extends WidgetIcon
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

        $this->setLabel(AmosDiscussioni::tHtml('amosdiscussioni', 'Discussioni di mio interesse'));
        $this->setDescription(AmosDiscussioni::t('amosdiscussioni', 'Elenco discussioni di mio interesse'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('disc');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('comment');
        }

        $this->setUrl(['/discussioni/discussioni-topic/own-interest-discussions']);
        $this->setCode('DISCUSSIONI_DI_MIO_INTERESSE');
        $this->setModuleName('discussioni');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(), $paramsClassSpan
        ));

        // Read and reset counter from bullet_counters table, bacthed calculated!
        if ($this->disableBulletCounters == false) {
            $this->setBulletCount(
                BulletCounters::getAmosWidgetIconCounter(
                    Yii::$app->getUser()->getId(), AmosDiscussioni::getModuleName(), $this->getNamespace(),
                    $this->resetBulletCount(), WidgetIconDiscussioniTopicAll::className()
                )
            );
        }
    }
}