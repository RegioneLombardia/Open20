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
class WidgetIconCwhNodi extends WidgetIcon
{

    /**
     * Init of the class, set of general configurations
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosCwh::t('amoscwh', 'CWH nodi'));
        $this->setDescription(AmosCwh::t('amoscwh', 'CWH gestione Nodi'));
        $this->setIcon('cwh');
        $this->setUrl(Yii::$app->urlManager->createUrl(['cwh/cwh-nodi']));
        $this->setCode('CWH_NODI');
        $this->setModuleName('cwh-nodi');
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
