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
//use open20\amos\partnershipprofiles\models\PartnershipProfiles;
//use open20\amos\partnershipprofiles\models\search\PartnershipProfilesSearch;
use open20\amos\partnershipprofiles\Module;

use open20\amos\utility\models\BulletCounters;

use Yii;

/**
 * Class WidgetIconPartnershipProfilesAll
 * @package open20\amos\partnershipprofiles\widgets\icons
 */
class WidgetIconPartnershipProfilesAll extends WidgetIcon
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

        $this->setLabel(Module::tHtml('amospartnershipprofiles', 'All'));
        $this->setDescription(Module::t('amospartnershipprofiles', 'All Partnership Profiles entities'));

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

        $this->setUrl(['/partnershipprofiles/partnership-profiles/index']);
        $this->setCode('PARTNERSHIP_PROFILES_ALL');
        $this->setModuleName('partnershipprofiles');
        $this->setNamespace(__CLASS__);

        // Read and reset counter from bullet_counters table, bacthed calculated!
        if ($this->disableBulletCounters == false) {
            $this->setBulletCount(
                BulletCounters::getAmosWidgetIconCounter(
                    Yii::$app->getUser()->getId(), 
                    Module::getModuleName(),
                    $this->getNamespace(),
                    $this->resetBulletCount()
                )
            );
        }
        
//        if ($this->disableBulletCounters == false) {
//            $search = new PartnershipProfilesSearch();
//            $search->setEventAfterCounter();
//            $query = $search->searchAllQuery([]);
//            
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    PartnershipProfiles::class,
//                    $query
//                )
//            );
//            
//            \Yii::$app->session->set('_offQuery', $query);
//            $this->trigger(self::EVENT_AFTER_COUNT);
//        }
    }
}
