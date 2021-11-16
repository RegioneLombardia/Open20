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
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\translation\AmosTranslation;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconTranslation extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosTranslation::tHtml('amostranslation', 'Translations'));
        $this->setDescription(AmosTranslation::t('amostranslation', 'Plugin Translation'));
        $this->setIcon('translate');
        $this->setIconFramework('am');
        $this->setUrl(Yii::$app->urlManager->createUrl(['/translation']));
        $this->setCode('TRANSLATE_MANAGER');
        $this->setModuleName('translation');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-lightPrimary'
                ]
            )
        );
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @return type
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => $this->getWidgetsIcon()]
        );
    }

    /**
     * 
     * @return type
     */
    public function getWidgetsIcon()
    {
        return AmosWidgets::find()
            ->andWhere(['child_of' => self::className()])
            ->all();
    }

}
