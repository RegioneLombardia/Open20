<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\widgets\icons
 * @category   CategoryName
 */

namespace backend\modules\thematics\widgets\icons;

use backend\modules\campains\Module;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetOperations
 * @package backend\modules\thematics\widgets\icons
 */
class WidgetThematic extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(Module::t('thematics', 'Tematiche'));
        $this->setDescription(Module::t('thematics', 'Tematiche'));

        $this->setIconFramework(AmosIcons::AM);
        $this->setIcon('format-list-bulleted');

        $this->setUrl(['/thematics/thematic']);
        $this->setCode('THEMATICS');
        $this->setModuleName('thematics');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-greyColor'
        ]));

    }


}
