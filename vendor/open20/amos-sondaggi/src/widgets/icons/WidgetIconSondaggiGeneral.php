<?php

namespace open20\amos\sondaggi\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\AmosSondaggi;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\utility\models\BulletCounters;

/**
 * Class WidgetIconAmministraSondaggi
 * @package open20\amos\sondaggi\widgets
 */
class WidgetIconSondaggiGeneral extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-primary'
        ];

        $this->setLabel(AmosSondaggi::tHtml('amossondaggi', 'Sondaggi'));
        $this->setDescription(AmosSondaggi::t('amossondaggi', 'Modulo Sondaggi'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('sondaggi');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('quote-right');
        }

        $this->setUrl('/sondaggi/pubblicazione/own-interest');
        $this->setCode('AMM_SONDAGGI');
        $this->setModuleName('sondaggi');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(), $paramsClassSpan
            )
        );

        // Read and reset counter from bullet_counters table, bacthed calculated!
        if ($this->disableBulletCounters == false) {
            $widgetAll = \Yii::createObject(['class' => WidgetIconCompilaSondaggiAll::className(), 'saveMicrotime' => false]);
            $this->setBulletCount(
                $widgetAll->getBulletCount()
            );
        }
    }
}