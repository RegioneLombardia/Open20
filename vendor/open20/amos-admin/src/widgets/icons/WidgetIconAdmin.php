<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\admin\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;

use open20\amos\admin\AmosAdmin;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconAdmin
 * @package open20\amos\admin\widgets\icons
 */
class WidgetIconAdmin extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-darkGrey'
        ];

        $this->setLabel(AmosAdmin::tHtml('amosadmin', 'Users'));
        $this->setDescription(AmosAdmin::t('amosadmin', 'Users'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('user');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('users');
        }

        $this->setUrl(['/'. AmosAdmin::getModuleName()]);
        $this->setCode('ADMIN_MODULE');
        $this->setModuleName(AmosAdmin::getModuleName());
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }

    /**
     * @inheritdoc
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
        $widgets = [];

        $MyProfile = new WidgetIconMyProfile();
        if ($MyProfile->isVisible()) {
            $widgets[] = $MyProfile->getOptions();
        }

        $UserProfile = new WidgetIconUserProfile();
        if ($UserProfile->isVisible()) {
            $widgets[] = $UserProfile->getOptions();
        }

        return $widgets;
    }

}
