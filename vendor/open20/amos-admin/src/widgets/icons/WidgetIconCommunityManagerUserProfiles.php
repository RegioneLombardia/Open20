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
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\search\UserProfileSearch;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconCommunityManagerUserProfiles
 * @package open20\amos\admin\widgets\icons
 */
class WidgetIconCommunityManagerUserProfiles extends WidgetIcon
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

        $this->setLabel(AmosAdmin::tHtml('amosadmin', 'Community Managers'));
        $this->setDescription(AmosAdmin::t('amosadmin', 'List of community manager users'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('user');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('users');
        }

        $this->setUrl([ '/'. AmosAdmin::getModuleName(). '/user-profile/community-manager-users']);
        $this->setCode('COMMUNITY_MANAGER_USERS');
        $this->setModuleName('admin');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

        $params = [];
        $query = new UserProfileSearch();
        $dataProvvider = $query->searchCommunityManagerUsers($params);

        $this->setBulletCount(
            $this->makeBulletCounter(
                Yii::$app->getUser()->getId(),
                AmosAdmin::instance()->model('UserProfile'),
                $dataProvvider->query
            )
        );

    }

}
