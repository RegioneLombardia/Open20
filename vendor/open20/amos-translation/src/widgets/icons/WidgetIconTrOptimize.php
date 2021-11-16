<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation
 * @category   CategoryName
 */

namespace open20\amos\translation\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\translation\AmosTranslation;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconTrOptimize extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosTranslation::tHtml('amostranslation', 'Optimize'));
        $this->setDescription(AmosTranslation::t('amostranslation', 'Optimize tables'));
        $this->setIcon('translate');
        $this->setIconFramework('am');
        $this->setUrl(Yii::$app->urlManager->createUrl(['/translatemanager/language/optimizer']));
        $this->setCode('TRANSLATION_OPTIMIZE');
        $this->setModuleName('translation');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-grey'
                ]
            )
        );
    }

}
