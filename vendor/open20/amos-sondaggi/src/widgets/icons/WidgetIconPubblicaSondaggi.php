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
use Yii;
use open20\amos\core\icons\AmosIcons;
use yii\helpers\ArrayHelper;
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class WidgetIconPubblicaSondaggi
 * @package open20\amos\sondaggi\widgets\icons
 */
class WidgetIconPubblicaSondaggi extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosSondaggi::tHtml('amossondaggi', 'Pubblica sondaggi'));
        $this->setDescription(AmosSondaggi::t('amossondaggi', 'Permette di pubblicare i sondaggi'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('sondaggi');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('quote-right');
        }

        $this->setUrl(['/sondaggi/pubblicazione/pubblicazione']);
        $this->setCode('PUBB_SONDAGGI');
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
