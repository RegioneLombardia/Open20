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
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconExpressionsOfInterestAllAdmin
 * @package open20\amos\partnershipprofiles\widgets\icons
 */
class WidgetIconExpressionsOfInterestAllAdmin extends WidgetIcon
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

        $this->setLabel(Module::tHtml('amospartnershipprofiles', 'Expressions Of Interest Admin'));
        $this->setDescription(Module::t('amospartnershipprofiles', 'All Expressions Of Interest entities'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
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

        $this->setUrl(['/partnershipprofiles/expressions-of-interest/all-admin']);
        $this->setCode('EXPRESSIONS_OF_INTEREST_ALL_ADMIN');
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
