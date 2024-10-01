<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\tag\AmosTag;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconTag extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosTag::tHtml('amostag', 'Tag'));
        $this->setDescription(AmosTag::t('amostag', 'Elenco dei widgets del plugin Tag'));
        $this->setIcon('tag');
        $this->setUrl(Yii::$app->urlManager->createUrl(['tag']));
        $this->setCode('TAG_MODULE');
        $this->setModuleName('tag');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-secondary'
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
     * @return type
     */
    public function getWidgetsIcon()
    {
        $widgets = [];

        //istanza di MyProfile
        $TagManager = new WidgetIconTagManager();
        if ($TagManager->isVisible()) {
            $widgets[] = $TagManager->getOptions();
        }

        return $widgets;
    }

}
