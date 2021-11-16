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

class WidgetIconAmmComuni extends WidgetIcon
{

    public function init()
    {
        parent::init();

        $this->setLabel(AmosComuni::t('amoscomuni', 'Comuni'));
        $this->setDescription(AmosComuni::t('amoscomuni', 'Elenco dei widget di amministrazione del plugin Comuni'));

        $this->setIcon('balance');
        $this->setIconFramework('am');
        $this->setUrl(Yii::$app->urlManager->createUrl(['/comuni/dashboard/index']));
        $this->setCode('ADMIN_COMUNI');
        $this->setModuleName('comuni');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-grey'
        ]));

    }

    public function getOptions()
    {
        $options = parent::getOptions();

        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => $this->getWidgetsIcon()]);
    }

    public function getWidgetsIcon()
    {
        return AmosWidgets::find()
            ->andWhere([
                'child_of' => self::className()
            ])->all();
    }
}