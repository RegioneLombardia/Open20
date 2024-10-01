<?php

namespace open20\amos\comuni\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\comuni\AmosComuni;

class WidgetIconImportatoreCodiciCatastali extends WidgetIcon
{

    public function init()
    {
        parent::init();

        $this->setLabel(AmosComuni::t('amoscomuni', 'Aggiornamento Codici Catastali'));
        $this->setDescription(AmosComuni::t('amoscomuni', 'Aggiornamento Codici Catastali'));

        $this->setIcon('linentita');
        $this->setIconFramework('dash');
        $this->setUrl(Yii::$app->urlManager->createUrl(['/comuni/importatore/import-codici-catastali']));
        $this->setModuleName('comuni');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-grey'
        ]));
    }

}
