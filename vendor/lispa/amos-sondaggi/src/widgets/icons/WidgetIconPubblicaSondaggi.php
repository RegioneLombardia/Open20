<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\sondaggi\widgets\icons
 * @category   CategoryName
 */

namespace lispa\amos\sondaggi\widgets\icons;

use lispa\amos\core\widget\WidgetIcon;
use lispa\amos\sondaggi\AmosSondaggi;
use Yii;
use lispa\amos\core\icons\AmosIcons;
use yii\helpers\ArrayHelper;
use lispa\amos\core\widget\WidgetAbstract;

/**
 * Class WidgetIconPubblicaSondaggi
 * @package lispa\amos\sondaggi\widgets\icons
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
