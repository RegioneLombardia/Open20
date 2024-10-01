<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\widgets\icons;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetIcon;

use open20\amos\sondaggi\AmosSondaggi;

use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconSondaggiAdministration
 * @package open20\amos\sondaggi\widgets\icons
 */
class WidgetIconSondaggiAdministration extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosSondaggi::tHtml('amossondaggi', 'Amministra i Sondaggi'));
        $this->setDescription(AmosSondaggi::t('amossondaggi', 'Modulo di amministrazione dei Sondaggi'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('sondaggi');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('quote-right');
        }

        $this->setUrl(['sondaggi']);
        $this->setCode('AMM_SONDAGGI');
        $this->setModuleName('sondaggi');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-primary'
                ]
            )
        );
    }

}
