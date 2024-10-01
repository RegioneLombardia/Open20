<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\widgets\icons;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\partnershipprofiles\Module;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconPartnershipProfilesOwnInterest
 * @package open20\amos\partnershipprofiles\widgets\icons
 */
class WidgetIconPartnershipProfilesOwnInterest extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-primary'
        ];

        $this->setLabel(Module::tHtml('amospartnershipprofiles', 'Own Interests'));
        $this->setDescription(Module::t('amospartnershipprofiles', 'Show the partnership profiles of your own interest'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $customIcon = Module::instance()->pluginCustomIcon;
            if (strlen($customIcon) > 0) {
                $this->setIcon($customIcon);
            } else {
                $this->setIcon('propostecollaborazione');
            }
            $paramsClassSpan = [];
        } else {
            $customIcon = Module::instance()->pluginCustomIcon;
            if (strlen($customIcon) > 0) {
                $this->setIcon($customIcon);
            } else {
                $this->setIcon('partnership-profiles');
            }
        }

        $this->setUrl(['/partnershipprofiles/partnership-profiles/own-interest']);
        $this->setCode('PARTNERSHIP_PROFILES_OWN_INTEREST');
        $this->setModuleName('partnershipprofiles');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }
}
