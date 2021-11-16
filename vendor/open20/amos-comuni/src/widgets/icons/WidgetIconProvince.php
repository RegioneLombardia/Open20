<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

namespace open20\amos\comuni\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\comuni\AmosComuni;

class WidgetIconProvince extends WidgetIcon
{

    public function init()
    {
        parent::init();

        $this->setLabel(AmosComuni::t('amoscomuni', 'Elenco province'));
        $this->setDescription(AmosComuni::t('amoscomuni', 'Consente di modificare le province'));

        $this->setIcon('balance');
        $this->setIconFramework('am');
        $this->setUrl(Yii::$app->urlManager->createUrl(['/comuni/istat-province/index']));
        $this->setCode('ISTAT_PROVINCE');
        $this->setModuleName('comuni');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-grey'
        ]));
    }

}