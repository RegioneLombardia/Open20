<?php

namespace open20\video\widgets\icons;

use open20\amos\core\record\Record;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use open20\video\Module;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconVideo extends WidgetIcon
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

        $this->setLabel(Module::tHtml('video', 'Video'));
        $this->setDescription(Module::t('amosvideo', 'Plugin di video'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::AM);
            $this->setIcon('graduation-cap');
            $paramsClassSpan = [];
        } else {
            $this->setIconFramework('dash');
            $this->setIcon('video-camera');
        }

        $this->setUrl(Yii::$app->urlManager->createUrl(['/video/video/all']));
        $this->setModuleName('video');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

        // Read and reset counter from bullet_counters table, bacthed calculated!

        if ($this->disableBulletCounters == false ) {
            $this->setBulletCount(Record::getStaticBullet(Record::BULLET_TYPE_ALL, false, 'video', false, true));
        }
    }
}