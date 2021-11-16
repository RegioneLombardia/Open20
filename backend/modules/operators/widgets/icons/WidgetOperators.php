<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\widgets\icons
 * @category   CategoryName
 */

namespace backend\modules\operators\widgets\icons;

use backend\modules\campains\Module;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetOperations
 * @package backend\modules\operators\widgets\icons
 */
class WidgetOperators extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(Module::t('operators', 'Operatori'));
        $this->setDescription(Module::t('operators', 'Operatori'));

        $this->setIconFramework(AmosIcons::AM);
        $this->setIcon('assignment-account');

        $this->setUrl(['/operators/operator/index']);
        $this->setCode('OPERATORS');
        $this->setModuleName('operators');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-greyColor'
        ]));

    }


}
