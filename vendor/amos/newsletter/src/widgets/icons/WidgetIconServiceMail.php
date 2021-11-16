<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\newsletter\widgets\icons
 * @category   CategoryName
 */

namespace amos\newsletter\widgets\icons;

use amos\newsletter\Module;
use open20\amos\core\widget\WidgetIcon;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconSiteManagementElement
 * @package amos\newsletter\widgets\icons
 */
class WidgetIconServiceMail extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(Module::tHtml('amosnewsletter', 'Gestione servizio email'));
        $this->setDescription(Module::t('amosnewsletter', 'Gestione servizio email'));
        $this->setIcon('printarea');
        $this->setUrl(['/newsletter/service-email/index']);
        $this->setCode('SERICE_MAIL');
        $this->setModuleName('newsletter');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }
}
