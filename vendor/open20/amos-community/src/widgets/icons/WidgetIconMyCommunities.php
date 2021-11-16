<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\community\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\search\CommunitySearch;
use open20\amos\utility\models\BulletCounters;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconMyCommunities
 * @package open20\amos\community\widgets\icons
 */
class WidgetIconMyCommunities extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
        ];

        $this->setLabel(AmosCommunity::tHtml('amoscommunity', 'My communities'));
        $this->setDescription(AmosCommunity::t('amoscommunity', 'View the list of my communities'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('community');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('group');
        }

        $url       = ['/community/community/my-communities'];
        $moduleCwh = Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $url = ['/community/subcommunities/my-communities', 'id' => $scope['community']];
            }
        }

        $this->setUrl($url);
        $this->setCode('COMMUNITY');
        $this->setModuleName('community');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(), $paramsClassSpan
            )
        );

        if ($this->disableBulletCounters == false) {
            $this->setBulletCount(
                BulletCounters::getAmosWidgetIconCounter(
                    Yii::$app->getUser()->getId(), AmosCommunity::getModuleName(), $this->getNamespace(),
                    $this->resetBulletCount(), WidgetIconCommunity::className()
                )
            );
        }
    }

    /**
     * 
     * @return type
     */
    public static function widgetLabel()
    {
        return AmosCommunity::t('amoscommunity', 'My communities');
    }
}