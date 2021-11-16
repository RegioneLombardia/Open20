<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

namespace open20\amos\emailmanager\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\emailmanager\AmosEmail;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconTemplate extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosEmail::tHtml('amosemail', 'Template'));
        $this->setDescription(AmosEmail::t('amosemail', 'Template Widget'));
        $this->setIcon('envelope-o');
        $this->setUrl(Yii::$app->urlManager->createUrl(['email/template']));
        $this->setCode('EMAIL_MANAGER_MODULE');
        $this->setModuleName('email');
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
     * TEMPORANEA
     *
     * @return array
     */
    public function getWidgetsIcon()
    {
        $widgets = [];

        return $widgets;
    }

}
