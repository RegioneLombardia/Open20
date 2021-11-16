<?php

namespace open20\amos\comuni\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\comuni\AmosComuni;

class WidgetIconImportatoreCap extends WidgetIcon
{

    public function init()
    {
        parent::init();

        $this->setLabel(AmosComuni::t('amoscomuni', 'Importatore CAP'));
        $this->setDescription(AmosComuni::t('amoscomuni', 'Gestione delle importazioni CAP'));

        $this->setIcon('linentita');
        $this->setIconFramework('dash');
        $this->setUrl(Yii::$app->urlManager->createUrl(['/comuni/importatore/import-cap']));
        $this->setModuleName('comuni');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-grey'
        ]));
    }

}
