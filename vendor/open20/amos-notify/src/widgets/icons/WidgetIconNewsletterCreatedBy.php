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
 * Class WidgetIconNewsletterCreatedBy
 * @package open20\amos\notificationmanager\widgets\icons
 */
class WidgetIconNewsletterCreatedBy extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosNotify::txt('#WidgetIconNewsletterCreatedBy_label'));
        $this->setDescription(AmosNotify::txt('#WidgetIconNewsletterCreatedBy_description'));
        $this->setIcon('printarea');
        $this->setUrl(['/notify/newsletter/created-by']);
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
