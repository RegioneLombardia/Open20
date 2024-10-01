<?php

namespace open20\amos\community\widgets\icons;

use open20\amos\core\record\Record;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use open20\amos\community\AmosCommunity;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconBookmarks extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ];

        $this->setLabel(AmosCommunity::tHtml('amoscommunity', 'Bookmarks'));
        $this->setDescription(AmosCommunity::t('amoscommunity', 'Modulo Bookmarks'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::AM);
            $this->setIcon('bookmark');
            $paramsClassSpan = [];
        } else {
            $this->setIconFramework('dash');
            $this->setIcon('video-camera');
        }

        $moduleCwh = Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
            }
        }
        $this->setUrl(Yii::$app->urlManager->createUrl(['/community/bookmarks/index', 'id' => $communityId]));
        $this->setModuleName('community');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

        // Read and reset counter from bullet_counters table, bacthed calculated!

        if ($this->disableBulletCounters == false ) {
            $this->setBulletCount(Record::getStaticBullet(Record::BULLET_TYPE_ALL, false, 'bookmarks', false, true));
        }
    }
}