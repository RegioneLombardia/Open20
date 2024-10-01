<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\widgets\icons
 * @category   CategoryName
 */

namespace amos\sitemanagement\widgets\icons;

use amos\sitemanagement\Module;
use open20\amos\core\widget\WidgetIcon;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconSMCommunitySliderElementsImages
 * @package amos\sitemanagement\widgets\icons
 */
class WidgetIconSMCommunitySliderElementsImages extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(Module::tHtml('amossitemanagement', 'Images'));
        $this->setDescription(Module::t('amossitemanagement', 'Images'));
        $this->setIcon('image');
        $this->setUrl(['/sitemanagement/site-management-community-slider-elements/images']);
        $this->setCode('SM_COMMUNITY_SLIDER_ELEMENTS_IMAGES');
        $this->setModuleName('sitemanagement');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ]));
    }
}
