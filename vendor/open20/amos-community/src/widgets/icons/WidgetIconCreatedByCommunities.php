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
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconCreatedByCommunities
 * @package open20\amos\community\widgets\icons
 */
class WidgetIconCreatedByCommunities extends WidgetIcon
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

        $this->setLabel(AmosCommunity::tHtml('amoscommunity', 'Communities created by me'));
        $this->setDescription(AmosCommunity::t('amoscommunity', 'View the list of communities created by me'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('community');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('group');
        }

        $url     = ['/community/community/created-by-communities'];
        $scopeId = $this->checkScope('community');
        if ($scopeId != false) {
            $url = ['/community/subcommunities/created-by-communities', 'id' => $scopeId];
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
    }

    /**
     * @return string
     */
    public static function widgetLabel()
    {
        return AmosCommunity::t('amoscommunity', 'Communities created by me');
    }
}