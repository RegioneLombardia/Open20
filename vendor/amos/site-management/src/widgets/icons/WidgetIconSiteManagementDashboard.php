<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\widgets\icons
 * @category   CategoryName
 */

namespace amos\sitemanagement\widgets\icons;

use amos\sitemanagement\Module;
use open20\amos\core\widget\WidgetIcon;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconSiteManagementDashboard
 * @package amos\sitemanagement\widgets\icons
 */
class WidgetIconSiteManagementDashboard extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(Module::tHtml('amossitemanagement', 'Site Management'));
        $this->setDescription(Module::t('amossitemanagement', 'Site Management'));
        $this->setIcon('linentita');
        $this->setUrl(['/sitemanagement']);
        $this->enableDashboardModal();
        $this->setCode('SITE_MANAGEMENT_DASHBOARD');
        $this->setModuleName('sitemanagement');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ]));
    }
}
