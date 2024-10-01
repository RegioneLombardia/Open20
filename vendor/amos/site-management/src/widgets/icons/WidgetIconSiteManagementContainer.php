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
 * Class WidgetIconSiteManagementContainer
 * @package amos\sitemanagement\widgets\icons
 */
class WidgetIconSiteManagementContainer extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(Module::tHtml('amossitemanagement', 'Container'));
        $this->setDescription(Module::t('amossitemanagement', 'Container'));
        $this->setIcon('linentita');
        $this->setUrl(['/sitemanagement/site-management-container/index']);
        $this->setCode('SITE_MANAGEMENT_CONTAINER');
        $this->setModuleName('sitemanagement');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ]));
    }
}
