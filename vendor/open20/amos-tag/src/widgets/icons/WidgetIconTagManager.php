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

class WidgetIconTagManager extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosTag::tHtml('amostag', '#widget_title_tag'));
        $this->setDescription(AmosTag::t('amostag', 'Consente all\'utente di gestire gli alberi di tag'));
        $this->setIcon('tag');
        $this->setUrl(Yii::$app->urlManager->createUrl(['tag/manager']));
        $this->setCode('TAG_MANAGER');
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

}
