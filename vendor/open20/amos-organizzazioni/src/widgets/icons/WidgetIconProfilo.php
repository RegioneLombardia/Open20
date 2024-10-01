<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\widgets\icons;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\organizzazioni\Module;
use open20\amos\utility\models\BulletCounters;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconProfilo
 *
 * This is the DASHBOARD widget!!!
 *
 * @package open20\amos\organizzazioni\widgets\icons
 */
class WidgetIconProfilo extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-lightBase'
        ];
        
        $this->setLabel(Module::tHtml('amosorganizzazioni', '#widget_icon_profilo_label'));
        $this->setDescription(Module::t('amosorganizzazioni', '#widget_icon_profilo_description'));
        
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('organizzazioni');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('building-o');
        }
        
        if (!\Yii::$app->user->isGuest) {
            $this->setUrl(['/organizzazioni/profilo/index']);
        }
        
        $this->setCode('PROFILO');
        $this->setModuleName('organizzazioni');
        $this->setNamespace(__CLASS__);
        
        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(), $paramsClassSpan
            )
        );
        
        // Read and reset counter from bullet_counters table, bacthed calculated!
        if ($this->disableBulletCounters == false) {
            $this->setBulletCount(
                BulletCounters::getAmosWidgetIconCounter(
                    \Yii::$app->getUser()->getId(), Module::getModuleName(), $this->getNamespace(),
                    $this->resetBulletCount()
                )
            );
        }
    }
}