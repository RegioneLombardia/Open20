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
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\utility\models\BulletCounters;
use Yii;

/**
 * Class WidgetIconCompilaSondaggiAll
 * @package open20\amos\sondaggi\widgets\icons
 */
class WidgetIconCompilaSondaggiAll extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosSondaggi::tHtml('amossondaggi', 'Tutti i sondaggi'));
        $this->setDescription(AmosSondaggi::t('amossondaggi', 'Permette di compilare e completare tutti i sondaggi'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('sondaggi');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('quote-right');
        }

        $this->setUrl(['/sondaggi/pubblicazione/all']);
        $this->setCode('COMP_SONDAGGI_ALL');
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

        // Read and reset counter from bullet_counters table, bacthed calculated!
        if ($this->disableBulletCounters == false) {
            $this->setBulletCount(
                BulletCounters::getAmosWidgetIconCounter(
                    Yii::$app->getUser()->getId(), AmosSondaggi::getModuleName(), $this->getNamespace(),
                    $this->resetBulletCount(), null, WidgetIconCompilaSondaggiOwnInterest::className(),
                    $this->saveMicrotime
                )
            );
        }
    }
}