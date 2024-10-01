<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

namespace open20\amos\slideshow\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\slideshow\AmosSlideshow;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconSlideshowConf extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosSlideshow::tHtml('amosslideshow', 'Slideshow'));
        $this->setDescription(AmosSlideshow::t('amosslideshow', 'Visualizza gli slideshow presenti'));
        $this->setIcon('image');
        if (!Yii::$app->user->isGuest) {
            $this->setUrl(\Yii::$app->urlManager->createUrl(['/slideshow/slideshow/index']));
        }

        $this->setCode('SLIDESHOW_LIST');
        $this->setModuleName('slideshow');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-darkPrimary'
                ]
            )
        );
    }

}
