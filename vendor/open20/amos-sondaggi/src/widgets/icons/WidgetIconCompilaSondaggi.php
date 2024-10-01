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

use open20\amos\core\widget\WidgetIcon;
use open20\amos\sondaggi\AmosSondaggi;
use yii\helpers\ArrayHelper;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;

/**
 * Class WidgetIconCompilaSondaggi
 * @package open20\amos\sondaggi\widgets\icons
 */
class WidgetIconCompilaSondaggi extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosSondaggi::tHtml('amossondaggi', 'Compila sondaggi'));

        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                if ($scope['community'] == 2831 && isset(\Yii::$app->params['isPoi']) && (\Yii::$app->params['isPoi'] === true)) {
                    $this->setLabel(AmosSondaggi::tHtml('amossondaggi', 'Monitoraggio interventi'));
                }
            }
        }

        $this->setDescription(AmosSondaggi::t('amossondaggi', 'Permette di compilare e completare i sondaggi'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('sondaggi');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('quote-right');
        }

        $this->setUrl(['/sondaggi/pubblicazione/own-interest']);
        $this->setCode('COMP_SONDAGGI');
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

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @inheritdoc
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => []]
        );
    }

}
