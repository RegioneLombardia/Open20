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
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconProfiloGroups
 * @package open20\amos\organizzazioni\widgets\icons
 */
class WidgetIconProfiloGroups extends WidgetIcon
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
        
        $this->setLabel(Module::tHtml('amosorganizzazioni', '#widget_icon_profilo_groups_label'));
        $this->setDescription(Module::t('amosorganizzazioni', '#widget_icon_profilo_groups_description'));
        
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('organizzazioni');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('building-o');
        }
        
        if (!\Yii::$app->user->isGuest) {
            $this->setUrl(['/organizzazioni/profilo-groups/index']);
        }
        
        $this->setCode('PROFILO_GROUPS');
        $this->setModuleName('organizzazioni');
        $this->setNamespace(__CLASS__);
        
        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(), $paramsClassSpan
            )
        );
    }
}
