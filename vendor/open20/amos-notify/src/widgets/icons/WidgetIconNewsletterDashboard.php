<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\notificationmanager\AmosNotify;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconNewsletterDashboard
 * @package open20\amos\notificationmanager\widgets\icons
 */
class WidgetIconNewsletterDashboard extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosNotify::txt('#WidgetIconNewsletterDashboard_label'));
        $this->setDescription(AmosNotify::txt('#WidgetIconNewsletterDashboard_description'));
        $this->setIcon('printarea');
        $this->setUrl(['/notify/newsletter/index']);
        $this->setCode('NEWSLETTER');
        $this->setModuleName('notify');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(ArrayHelper::merge(
            $this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ]));
    }
}
