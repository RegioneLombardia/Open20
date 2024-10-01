<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\widgets\icons;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\cwh\AmosCwh;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * TODO SOLO abbozzata per le regole e migration
 * Class WidgetIconCwhNodi
 *
 * @package open20\amos\cwh\widgets\icons
 */
class WidgetIconCwhAuthAssignment extends WidgetIcon
{

    /**
     * Init of the class, set of general configurations
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosCwh::t('amoscwh', 'CWH Auth Assignment'));
        $this->setDescription(AmosCwh::t('amoscwh', 'CWH gestione Auth Assignment'));
        $this->setIcon('cwh');
        $this->setUrl(Yii::$app->urlManager->createUrl(['cwh/cwh-auth-assignment']));
        $this->setCode('CWH_AUTH_ASSIGNEMNT');
        $this->setModuleName('cwh-auth-assignement');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-darkGrey'
                ]
            )
        );
    }

}
