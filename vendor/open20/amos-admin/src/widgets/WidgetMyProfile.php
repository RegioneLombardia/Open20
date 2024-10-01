<?php

namespace open20\amos\admin\widgets;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserContact;
use open20\amos\admin\models\UserProfile;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\user\User;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use open20\amos\tag\models\Tag;
use luya\cms\frontend\blockgroups\MainGroup;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Html;
use \open20\amos\admin\base\ConfigurationManager;

use yii\base\Widget;

class WidgetMyProfile extends Widget
{

    public $adminModule;
    public $model;
    public $totCounter = 0;


    /**
     * @param array $params
     * @return mixed|string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {

        if (!\Yii::$app->user->isGuest) {
            $this->adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());
            $this->model = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();

            $open = $this->isWidgetSeen();

            $params['model'] = $this->model;
            $params['adminModule'] = $this->adminModule;
            $params['showPrevalentPartnership'] = $this->canShowPartnershipProfiles();
            $params['prevalentPartnershipName'] = $this->getPrevalentPartnershipName();
            $params['avatarUrl'] = $this->getAvatar();
            $params['userProfileUrl'] = "/" . AmosAdmin::getModuleName() . "/user-profile/view?id=" . $this->model->id;
            $params['counters'] = $this->getContentCounters();
            $params['totCounter'] = $this->totCounter;
            $params['tags'] = $this->getTagOfInterest();
            $params['contacts'] = $this->getLastContacts();
            $params['communities'] = $this->getLastCommunities();
            $params['isCommunityManager'] = $this->isCommunityManager();
            $params['isFacilitator'] = $this->model->isFacilitator();
            $params['urlTagManager'] = "/" . AmosAdmin::getModuleName() . "/user-profile/update?id=" . $this->model->id . '#tab-tags';
            $params['additionalInfo'] = \Yii::t('app', 'Regione Lombardia');
            $params['open'] = !$open;

            $this->setWidgetSeen();

//pr($params['tags']);
            return $this->view->render('@vendor/open20/design/src/components/bootstrapitalia/views/bi-widget-myprofile', $params);
//            return $this->view->render($this->getViewFileName('php'), $params, $this);
        }
        return '';

    }

    /**
     * @return array
     */
    public function getContentCounters()
    {

        $counters = [];
        $totCounter = 0;


        $notifier = \Yii::$app->getModule('notify');
        $whiteList = $this->getWhiteList();
        foreach ($whiteList as $modelEnabled) {
            $config = $this->getMatchingQuery($modelEnabled);
//            pr($config,'model');
            if (!empty($config['class']) && !empty($config['query'])) {
                $queryFunction = $config['query'];
                $driverClass = $config['class'];
                $driver = \Yii::createObject([
                    'class' => $driverClass,
                    'module' => $config['module'],
                    'user_id' => \Yii::$app->user->id
                ]);
//                pr(get_class($driver),'class');

                if ($driver) {
                    $driver->$queryFunction();
                    $query = $driver->query;
                    $counter = 0;
                    if ($notifier) {
//                        pr($modelEnabled, 'model');
                        $counter = $notifier->countNotRead(
                            \Yii::$app->user->id, $modelEnabled, $query
                        );
                        if ($counter > 0) {
                            $counters [] = [
                                'classname' => $modelEnabled,
                                'module' => $config['module'],
                                'counter' => $counter,
                                'name' => $config['label'],
                                'url' => $config['url'],
                            ];
                            $totCounter += $counter;
                        }
                    }
                }

            }


        }

        $this->totCounter = $totCounter;
        return $counters;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getTagOfInterest()
    {
        $loggedUserProfile = UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
        $tags = Tag::find()
            ->innerJoin('cwh_tag_owner_interest_mm', 'cwh_tag_owner_interest_mm.tag_id = tag.id')
            ->andWhere(['record_id' => $loggedUserProfile->id])
            ->andWhere(['classname' => 'open20\amos\admin\models\UserProfile'])
            ->andWhere(['interest_classname' => 'simple-choice'])->all();

//        foreach ($tags as $tag){
//            pr($tag->id, $tag->nome);
//
//        }

        return $tags;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getLastContacts()
    {
        $users = [];
        $contacts = UserContact::find()
            ->andWhere(['OR',
                ['user_id' => \Yii::$app->user->id],
                ['contact_id' => \Yii::$app->user->id]
            ])->limit(5)->orderBy('id Desc')->all();

        /** @var  $contact UserContact */
        foreach ($contacts as $contact) {
            if ($contact->user_id != \Yii::$app->user->id) {
                $userProfile = UserProfile::find()->andWhere(['user_id' => $contact->user_id, 'attivo'=>1])->one();
            } else {
                $userProfile = UserProfile::find()->andWhere(['user_id' => $contact->contact_id, 'attivo'=>1])->one();

            }
            if ($userProfile) {
                $users[] = [
                    'nomeCognome' => $userProfile->nomeCognome,
                    'avatarUrl' => $userProfile->getAvatarUrl('card_users'),
                    'userProfile' => $userProfile
                ];
            }
        }
        return $users;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getLastCommunities()
    {
        $lastCommunities = [];
        $communities = Community::find()
            ->innerJoin('community_user_mm', 'community_user_mm.community_id = community.id')
            ->andWhere(['community_user_mm.user_id' => \Yii::$app->user->id])
            ->andWhere(['community_user_mm.status' => CommunityUserMm::STATUS_ACTIVE])
            ->andWhere(['community.deleted_at' => null])
            ->andWhere(['community_user_mm.deleted_at' => null])
            ->limit(5)->orderBy('community_user_mm.id desc')->all();


        /** @var  $community Community */
        foreach ($communities as $community) {
            if (!empty($community->communityLogo)) {
                $urlLogo = $community->communityLogo->getWebUrl('item_community', false, true);
            } else {
                $urlLogo = '/img/img_default.jpg';
            }
            $lastCommunities [] = [
                'name' => $community->name,
                'communityLogoUrl' => $urlLogo,
                'id' => $community->id
            ];
        }
        return $lastCommunities;
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function isCommunityManager()
    {
        $count = CommunityUserMm::find()
            ->andWhere(['user_id' => \Yii::$app->user->id])
            ->andWhere(['role' => CommunityUserMm::ROLE_COMMUNITY_MANAGER])
            ->count();
        return $count > 0;
    }


    /**
     * @return string
     */
    public function getAvatar()
    {
        $model = $this->model;
        $avatarUrl = $model->getAvatarUrl('card_users');

        return $avatarUrl;
    }

    /**
     * @return string
     */
    public function getPrevalentPartnershipName()
    {
        $prevalentPartnershipTruncated = '';
        $prevalentPartnershipName = '';
        if (!is_null($this->model->prevalentPartnership)) {
            $prevalentPartnershipTruncated = $this->model->prevalentPartnership;
//            $prevalentPartnershipName = $this->model->prevalentPartnership->name;
        }
        return $prevalentPartnershipTruncated;
    }

    /**
     * @return bool
     */
    public function canShowPartnershipProfiles()
    {
        $showPrevalentPartnership = false;
        if (
            ($this->adminModule->confManager->isVisibleBox('box_prevalent_partnership', ConfigurationManager::VIEW_TYPE_VIEW))
            &&
            ($this->adminModule->confManager->isVisibleField('prevalent_partnership_id', ConfigurationManager::VIEW_TYPE_VIEW))
        ) {
            $showPrevalentPartnership = true;
        }
        return $showPrevalentPartnership;
    }


    /**
     * @param $modelClassname
     * @return mixed|null
     */
    public function getMatchingQuery($modelClassname)
    {
        $namespaceDriverUtility = 'open20\amos\utility\drivers';
        $query = [
            'open20\amos\news\models\News' => [
                'class' => $namespaceDriverUtility . '\bcDriverNews',
                'module' => 'news',
                'query' => 'searchWidgetIconNews',
                'label' => \Yii::t('app', "Notizie"),
                'url' => '/news/news/own-interest-news'
            ],
            'open20\amos\documenti\models\Documenti' => [
                'class' => $namespaceDriverUtility . '\bcDriverDocumenti',
                'module' => 'documenti',
                'query' => 'searchWidgetIconDocumenti',
                'label' => \Yii::t('app', "Documenti"),
                'url' => '/documenti/documenti/own-interest-documents'

            ],
            'open20\amos\discussioni\models\DiscussioniTopic' => [
                'class' => $namespaceDriverUtility . '\bcDriverDiscussioni',
                'module' => 'discussioni',
                'query' => 'searchWidgetIconDiscussioniTopic',
                'label' => \Yii::t('app', "Discussioni"),
                'url' => '/discussioni/discussioni-topic/own-interest-discussions'

            ],
            'open20\amos\een\models\EenPartnershipProposal' => [
                'class' => $namespaceDriverUtility . '\bcDriverEen',
                'module' => 'een',
                'query' => 'searchWidgetIconEen',
                'label' => \Yii::t('app', "Proposte dal mondo"),
                'url' => '/een/een-partership-proposal/own-interest-een'

            ],
            'open20\amos\partnershipprofiles\models\PartnershipProfiles' => [
                'class' => $namespaceDriverUtility . '\bcDriverPartnershipprofiles',
                'module' => 'partnershipprofiles',
                'query' => 'searchWidgetIconPartnershipProfilesOwnIntrerest',
                'label' => \Yii::t('app', "Proposte di collaborazione"),
                'url' => '/partnershipprofiles/partnership-profiles/own-interest'

            ]
        ];

        if (!empty($query[$modelClassname])) {
            return $query[$modelClassname];
        }
        return null;

    }


    /**
     * @return array
     */
    public function getWhiteList()
    {
        $whiteList = $this->adminModule->contentOfInterestWidgetMyProfile;
        return $whiteList;
    }

    /**
     *
     */
    public function setWidgetSeen(){
        \Yii::$app->session->set('widgetMyProfileSeen' , true);
    }

    /**
     * @return bool
     */
    public function isWidgetSeen(){
        $isSeen = \Yii::$app->session->get('widgetMyProfileSeen');
        return ($isSeen === true);

    }

}
