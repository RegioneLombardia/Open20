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
 * Class WidgetIconSiteManagementSection
 * @package amos\sitemanagement\widgets\icons
 */
class WidgetIconSiteManagementLandingFather extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(Module::tHtml('amossitemanagement', 'Advertising landing'));
        $this->setDescription(Module::t('amossitemanagement', 'Advertising landing'));
        $this->setIcon('linentita');
        $this->setUrl(['/sitemanagement/site-management-landing/index']);
        $this->setCode('SITE_MANAGEMENT_ADMINISTRATION');
        $this->setModuleName('sitemanagement');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ]));
    }
}
