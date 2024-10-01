<?php

namespace open20\amos\comuni\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\comuni\AmosComuni;

class WidgetIconVariazioniComuni extends WidgetIcon
{

    public function init()
    {
        parent::init();

        $this->setLabel(AmosComuni::t('amoscomuni', 'Variazioni Comuni'));
        $this->setDescription(AmosComuni::t('amoscomuni', 'Gestione delle variazioni Comuni'));

        $this->setIcon('linentita');
        $this->setIconFramework('dash');
        $this->setUrl(Yii::$app->urlManager->createUrl(['/comuni/importatore/import-variazioni-comuni']));
        $this->setModuleName('comuni');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-grey'
        ]));
    }

}
