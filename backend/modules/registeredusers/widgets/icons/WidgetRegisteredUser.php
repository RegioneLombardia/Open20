<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\widgets\icons
 * @category   CategoryName
 */

namespace backend\modules\registeredusers\widgets\icons;

use backend\modules\campains\Module;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetOperations
 * @package backend\modules\registeredusers\widgets\icons
 */
class WidgetRegisteredUser extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(Module::t('registeredusers', 'Utenti'));
        $this->setDescription(Module::t('registeredusers', 'Utenti'));

        $this->setIconFramework(AmosIcons::IC);
        $this->setIcon('user');

        $this->setUrl(['/registeredusers/registered-user/index']);
        $this->setCode('REGISTEREDUSERS');
        $this->setModuleName('registeredusers');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-greyColor'
        ]));

    }


}
