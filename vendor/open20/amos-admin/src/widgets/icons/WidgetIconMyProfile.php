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

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetIcon;

use open20\amos\admin\AmosAdmin;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconMyProfile
 * @package open20\amos\admin\widgets\icons
 */
class WidgetIconMyProfile extends WidgetIcon
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

        $this->setLabel(AmosAdmin::tHtml('amosadmin', 'Il mio profilo'));
        $this->setDescription(AmosAdmin::t('amosadmin', 'Consente all\'utente di modificare il proprio profilo'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('user');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('users');
        }

        if (!Yii::$app->user->isGuest) {
            $this->setUrl(
                [
                    '/'. AmosAdmin::getModuleName(). '/user-profile/update',
                    'id' => Yii::$app->getUser()->id
                ]
            );
        }

        $this->setCode('USER_PROFILE');
        $this->setModuleName(AmosAdmin::getModuleName());
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }

}
