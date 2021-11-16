<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\utility
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\utility;

use open20\amos\admin\models\UserContact;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\comments\models\Comment;
use open20\amos\core\models\base\ContentLikes;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\user\User;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\base\BuilderFactory;
use open20\amos\notificationmanager\models\base\NotificationConfContent;
use open20\amos\notificationmanager\models\NotificationConf;
use open20\amos\notificationmanager\models\NotificationconfNetwork;
use open20\amos\notificationmanager\models\NotificationLanguagePreferences;
use open20\amos\notificationmanager\models\NotificationsConfOpt;
use Yii;
use yii\base\BaseObject;
use yii\db\ActiveQuery;

/**
 * Class NotifyUtility
 * @package open20\amos\notificationmanager\utility
 */
class NotifyUtility extends BaseObject
{
    /**
     * @var AmosNotify $notifyModule
     */
    protected $notifyModule = null;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->notifyModule = AmosNotify::instance();
    }
    
    /**
     * The method save the notification configuration.
     * @param int $userId
     * @param int $emailFrequency
     * @param int $smsFrequency
     * @param array $params
     * @return bool
     */
    public function saveNotificationConf($userId, $emailFrequency = 0, $smsFrequency = 0, $params = [])
    {
        // Check the params type
        if (!is_numeric($userId) || !is_numeric($emailFrequency) || !is_numeric($smsFrequency)) {
            return false;
        }
        // Check the params presence
        if (!$emailFrequency && !$smsFrequency) {
            return false;
        }
        /** @var NotificationConf $notificationConfModel */
        $notificationConfModel = $this->notifyModule->createModel('NotificationConf');
        // Find the notification conf for the user
        $notificationConf = $notificationConfModel::findOne(['user_id' => $userId]);
        if (is_null($notificationConf)) {
            /** @var NotificationConf $notificationConf */
            $notificationConf = $this->notifyModule->createModel('NotificationConf');
            $notificationConf->user_id = $userId;
        }
        
        $emailFrequencyValues = NotificationsConfOpt::emailFrequencyValues();
        if ($emailFrequency) {
            /** @var NotificationsConfOpt $notificationConfOpt */
            $notificationConfOpt = $this->notifyModule->createModel('NotificationsConfOpt');
            // Check the params correct value for email frequency
            $emailFrequencyValues = $notificationConfOpt::emailFrequencyValues();
            if (!in_array($emailFrequency, $emailFrequencyValues)) {
                return false;
            }
            $notificationConf->email = $emailFrequency;
        }
        if ($smsFrequency) {
            /** @var NotificationsConfOpt $notificationConfOpt */
            $notificationConfOpt = $this->notifyModule->createModel('NotificationsConfOpt');
            // Check the params correct value for sms frequency
            $smsFrequencyValues = $notificationConfOpt::smsFrequencyValues();
            if (!in_array($smsFrequency, $smsFrequencyValues)) {
                return false;
            }
            $notificationConf->sms = $smsFrequency;
        }
        
        $modelAttributesList = $notificationConf->attributes();
        foreach ($modelAttributesList as $fieldName) {
            if (isset($params[$fieldName])) {
                $notificationConf->{$fieldName} = $params[$fieldName];
            }
        }
        
        if (isset($params['contatti_suggeriti_email_selector_name'])) {
            // Check the params correct value for contatti_suggeriti_email_selector_name
            if (!in_array($params['contatti_suggeriti_email_selector_name'], $emailFrequencyValues)) {
                return false;
            }
            $notificationConf->contatti_suggeriti_email = $params['contatti_suggeriti_email_selector_name'];
        }
        
        if (isset($params['contenuti_successo_email_selector_name'])) {
            // Check the params correct value for contenuti_successo_email_selector_name
            if (!in_array($params['contenuti_successo_email_selector_name'], $emailFrequencyValues)) {
                return false;
            }
            $notificationConf->contenuti_successo_email = $params['contenuti_successo_email_selector_name'];
        }
        
        if (isset($params['profilo_successo_email_selector_name'])) {
            // Check the params correct value for profilo_successo_email_selector_name
            if (!in_array($params['profilo_successo_email_selector_name'], $emailFrequencyValues)) {
                return false;
            }
            $notificationConf->profilo_successo_email = $params['profilo_successo_email_selector_name'];
        }
        
        if (isset($params['notifications_enabled'])) {
            $notificationConf->notifications_enabled = $params['notifications_enabled'];
        }
        if (isset($params['notify_content_pubblication'])) {
            $notificationConf->notify_content_pubblication = $params['notify_content_pubblication'];
        }
        if (isset($params['notify_comments'])) {
            $notificationConf->notify_comments = $params['notify_comments'];
        }
        if (isset($params['contatto_accettato_flag'])) {
            $notificationConf->contatto_accettato_flag = $params['contatto_accettato_flag'];
        }
        if (isset($params['periodo_inattivita_flag'])) {
            $notificationConf->periodo_inattivita_flag = $params['periodo_inattivita_flag'];
        }
        if (isset($params['notify_preference_language'])) {
            NotificationLanguagePreferences::deleteAll(['user_id' => $userId]);
            foreach ($params['notify_preference_language'] as $language) {
                $notifyLangPreferences = new NotificationLanguagePreferences();
                $notifyLangPreferences->user_id = $userId;
                $notifyLangPreferences->language = $language;
                $notifyLangPreferences->save(false);
            }
        }

        $ok = $notificationConf->save();

        if(isset($params['notify_contents'])){
            $this->saveNotificationConfContent($notificationConf, $params['notify_contents']);
        }
        $this->saveNetworkNotification($userId, $params);
        return $ok;
    }

    /**
     * @param $notificationConf
     * @param $params
     */
    public function saveNotificationConfContent($notificationConf, $params){
        NotificationConfContent::deleteAll(['notification_conf_id' => $notificationConf->id]);

        foreach ($params as $contentClassId => $configs){
            $conf = new NotificationConfContent();
            $conf->notification_conf_id = $notificationConf->id;
            $conf->models_classname_id = $contentClassId;
            $conf->push_notification = !empty($configs['push']) ? $configs['push'] : 0;
            $conf->email = !empty($configs['email']) ? $configs['email'] : 0;
            $conf->save(false);
        }
    }

    /**
     * @param $userId
     * @param $params
     */
    public function saveNetworkNotification($userId, $params)
    {
        if (!empty($params['notifyCommunity'])) {
            foreach ($params['notifyCommunity'] as $communityId => $value) {
                $modelClassname = ModelsClassname::find()->andWhere(['module' => 'community'])->one();
                if ($modelClassname) {
                    /** @var NotificationconfNetwork $notificationConfNetworkModel */
                    $notificationConfNetworkModel = $this->notifyModule->createModel('NotificationconfNetwork');
                    $confNetwork = $notificationConfNetworkModel::find()
                        ->andWhere(['models_classname_id' => $modelClassname->id, 'record_id' => $communityId])
                        ->andWhere(['user_id' => $userId])->one();
                    if (empty($confNetwork)) {
                        /** @var NotificationconfNetwork $confNetwork */
                        $confNetwork = $this->notifyModule->createModel('NotificationconfNetwork');
                    }
                    $confNetwork->user_id = $userId;
                    $confNetwork->models_classname_id = $modelClassname->id;
                    $confNetwork->record_id = $communityId;
                    $confNetwork->email = $value;
                    $confNetwork->save(false);
                }
            }
        }
    }
    
    /**
     * @param $userId
     * @param $notificationType
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getNetworkNotificationConf($userId, $notificationType)
    {
        /** @var NotificationconfNetwork $notificationConfNetworkModel */
        $notificationConfNetworkModel = AmosNotify::instance()->createModel('NotificationconfNetwork');
        /** @var  $query ActiveQuery */
        $query = $notificationConfNetworkModel::find()
            ->andWhere(['user_id' => $userId])
            ->andWhere(['IS NOT', 'record_id', null])
            ->andWhere(['IS NOT', 'models_classname_id', null]);
        
        $query->andWhere(['!=', 'email', $notificationType]);
        $query->andWhere(['IS NOT', 'email', null]);
        
        return $query->all();
    }
    
    /**
     * This method set the user default notifications configurations.
     * @param int $userId
     * @return bool
     */
    public function setDefaultNotificationsConfs($userId)
    {
        $emailFrequency = NotificationsConfOpt::EMAIL_DAY;
        $smsFrequency = 0;
        $params = [
            'notifications_enabled' => 1,
            'notify_content_pubblication' => 1,
            'notify_comments' => 1,
            'notify_newsletter' => 1,
            'notify_ticket_faq_referee' => 1,
            'contatto_accettato_flag' => 1,
            'periodo_inattivita_flag' => 1,
            'contatti_suggeriti_email' => $emailFrequency,
            'contenuti_successo_email' => $emailFrequency,
            'profilo_successo_email' => $emailFrequency,
        ];
        return $this->saveNotificationConf($userId, $emailFrequency, $smsFrequency, $params);
    }
    
    /**
     * This method add the notifications configurations for all users that these configurations are missing.
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function addMissingUserNotificationsConfs()
    {
        /** @var ActiveQuery $query */
        $query = User::find();
        $query->select(['id']);
        $allUserIds = $query->column();
        
        /** @var NotificationConf $notificationConfModel */
        $notificationConfModel = $this->notifyModule->createModel('NotificationConf');
        
        /** @var ActiveQuery $queryNotify */
        $queryNotify = $notificationConfModel::find();
        $queryNotify->select(['user_id']);
        $allNotificationConfsUserIds = $queryNotify->column();
        
        $missingNotificationConfsUserIds = array_diff($allUserIds, $allNotificationConfsUserIds);
        
        $allOk = true;
        foreach ($missingNotificationConfsUserIds as $userId) {
            $ok = $this->setDefaultNotificationsConfs($userId);
            if (!$ok) {
                $allOk = false;
            }
        }
        
        return $allOk;
    }
    
    /**
     * @param $color
     * @return array
     */
    public static function getColorNetwork($color)
    {
        $colors = [];
        if ($color == 'red') {
            $colors[] = '#962929';
            $colors[] = '#d44141';
            $colors[] = '#E07A7A';
        } else if ($color == 'blue') {
            $colors[] = '#002137';
            $colors[] = '#003354';
            $colors[] = '#8ea2b5';
        } else if ($color == 'green') {
            $colors[] = '#204d28';
            $colors[] = '#297a38';
            $colors[] = '#7cc588';
        } else {
            $colors[] = '#204d28';
            $colors[] = '#297a38';
            $colors[] = '#7cc588';
        }
        return $colors;
    }
    
    /**
     * @param $color
     * @return string
     */
    public static function getIconNetwork($color)
    {
        $icon = '/img/icon_emails/icon-';
        if ($color == 'red') {
            $icon .= 'lock-white.png';
        } else if ($color == 'blue') {
            $icon .= 'community-white.png';
        } else if ($color == 'green') {
            $icon .= 'community-white.png';
        } else {
            $icon .= 'community-white.png';;
        }
        return $icon;
    }
    
    /**
     * @param $modelNetwork
     * @param $i
     * @return string
     */
    public static function getTypeOfCommunitycolor($modelNetwork, $i)
    {
        if (!empty($modelNetwork->community_type_id) && $modelNetwork->community_type_id == 3) {
            $color = 'red';
        } else {
            if ($i % 2 == 0) {
                $color = 'green';
            } else {
                $color = 'blue';
            }
        }
        return $color;
    }
    
    /**
     * @param string $classname
     * @param string $color
     * @return string
     */
    public static function getIconPlugins($classname, $color)
    {
        $iconName = '/img//icon_emails/icon-';
        if ($classname == 'open20\amos\news\models\News') {
            $iconName .= 'news';
        } else if ($classname == 'open20\amos\discussioni\models\DiscussioniTopic') {
            $iconName .= 'discussioni';
        } else if ($classname == 'open20\amos\sondaggi\models\Sondaggi') {
            $iconName .= 'sondaggi';
        } else if ($classname == 'open20\amos\partnershipprofiles\models\PartnershipProfiles') {
            $iconName .= 'collaborazione';
        } else if ($classname == 'open20\amos\events\models\Event') {
            $iconName .= 'eventi';
        } else if ($classname == 'open20\amos\documenti\models\Documenti') {
            $iconName .= 'documenti';
        } else {
            /** @var AmosNotify $notifyModule */
            $notifyModule = AmosNotify::instance();
            if (isset($notifyModule->customIconPlugins[$classname])) {
                $iconName .= $notifyModule->customIconPlugins[$classname];
            } else {
                // Default icon
                $iconName .= 'news';
            }
        }
        $iconName .= '-' . $color . '.png';
        return $iconName;
    }
    
    public function contactAccepted($mainUser, $invitedUser)
    {
        
        //pr($mainUser->toArray());
        //pr($invitedUser->toArray());//exit;
        $factory = new BuilderFactory();
        $builder = $factory->create(BuilderFactory::CONTENT_CONTACT_ACCEPTED_BUILDER);
        
        $notificationconf = NotificationConf::find()->andWhere(['user_id' => $mainUser->id])->one();
        if (!empty($notificationconf) &&
            (is_null($notificationconf->notifications_enabled) || $notificationconf->notifications_enabled) &&
            $notificationconf->contatto_accettato_flag) {
            if (false) {
                print "Mando allo user " . $mainUser->id . " i dati del contatto " . $invitedUser->id . "<br />";
            }
            $this->sendUserInformation($invitedUser, $mainUser, $builder);
        }
        
        $notificationconf = NotificationConf::find()->andWhere(['user_id' => $invitedUser->id])->one();
        if (!empty($notificationconf) &&
            (is_null($notificationconf->notifications_enabled) || $notificationconf->notifications_enabled) &&
            $notificationconf->contatto_accettato_flag) {
            if (false) {
                print "Mando al contatto " . $invitedUser->id . " i dati dello user " . $mainUser->id . "<br />";
            }
            $this->sendUserInformation($mainUser, $invitedUser, $builder);
        }
    }
    
    public function sendUserInformation($aboutUser, $toUser, $builder)
    {
        
        $results = $this->findUserData($aboutUser->id, $toUser->id);
        
        // invia la mail coi contenuti
        if (!empty($results)) {
            $builder->sendEmailUserNotify([$toUser->id], $results);
        }
        
    } // sendUserInformation
    
    // A) contenuti creati da $aboutUser che $toUser può vedere
    // B) contenuti like da $aboutUser che $toUser può vedere
    // C) contenuti commentati da $aboutUser che $toUser può vedere
    // D) ultimi utenti entrati in contatto con $aboutUser
    protected function findUserData($aboutUserId, $toUserId)
    {
        
        $results = [];
        $debugMe = false;
        $idsCountsGlobal = [];
        $module = AmosNotify::getInstance();
        
        $modelsEnabled = \open20\amos\cwh\models\CwhConfigContents::find()->addSelect('classname')->column();
        $cwhModule = Yii::$app->getModule('cwh');
        if (isset($cwhModule)) {
            foreach ($modelsEnabled as $classname) {
                if ($debugMe) {
                    print 'findUserContentData - $modelsEnabled $classname ' . $classname . '<br />' . "\n";
                } // $debugMe
                
                // A) contenuti creati da $aboutUser che $toUser può vedere
                $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery($classname, [
                    'queryBase' => $classname::find(),
                    'userId' => $toUserId
                ]);
                $cwhActiveQuery::$userProfile = null; //reset user profile
                
                $queryId = $cwhActiveQuery->getQueryCwhOwnInterest();
                $queryId->andWhere(['created_by' => $aboutUserId])
                    ->orderBy(['created_at' => SORT_DESC])
                    ->limit($module->contentsLimit);
                $res = $queryId->all();
                if ($debugMe) {
                    print 'A) contenuti creati da $aboutUser che $toUser può vedere $queryId' . '<br />' . "\n";
                    //print $queryId->createCommand()->rawSql.'<br />'."\n";
                    print '$queryId - trovati ' . count($res) . '<br />' . "\n";
                } // $debugMe
                unset($queryId);
                if (count($res) > 0) {
                    $results['created_by'][$classname] = $res;
                }
                
                
                // C) contenuti commentati da $aboutUser che $toUser può vedere
                $queryId = $cwhActiveQuery->getQueryCwhOwnInterest();
                $queryId->innerJoin(Comment::tableName(), [
                    $classname::tableName() . '.id' => new \yii\db\Expression(Comment::tableName() . '.context_id'),
                    Comment::tableName() . '.context' => $classname
                ])
                    ->andWhere([Comment::tableName() . '.created_by' => $aboutUserId])  // ha fatto lui il commento
                    ->andWhere(['not', [$classname::tableName() . '.created_by' => $aboutUserId]])   // non è lui l'autore del contenuto
                    ->orderBy([Comment::tableName() . '.created_at' => SORT_DESC])
                    ->limit($module->contentsLimit);
                $res = $queryId->all();
                if ($debugMe) {
                    print 'C) contenuti commentati da $aboutUser che $toUser può vedere' . '<br />' . "\n";
                    //print $queryId->createCommand()->rawSql.'<br />'."\n";
                    print '$queryId - trovati ' . count($res) . '<br />' . "\n";
                } // $debugMe
                unset($queryId);
                if (count($res) > 0) {
                    $results['commented_by'][$classname] = $res;
                }
                
                
                // B) contenuti like da $aboutUser che $toUser può vedere
                $queryId = $cwhActiveQuery->getQueryCwhOwnInterest();
                $queryId->innerJoin(ModelsClassname::tableName(), [
                    ModelsClassname::tableName() . '.classname' => $classname
                ])
                    ->innerJoin(ContentLikes::tableName(), [
                        ModelsClassname::tableName() . '.id' => new \yii\db\Expression(ContentLikes::tableName() . '.models_classname_id'),
                        $classname::tableName() . '.id' => new \yii\db\Expression(ContentLikes::tableName() . '.content_id')
                    ])
                    ->where([   // ha messo lui il like
                        ContentLikes::tableName() . '.likes' => 1,
                        ContentLikes::tableName() . '.created_by' => $aboutUserId,
                    ])
                    ->andWhere(['not', [$classname::tableName() . '.created_by' => $aboutUserId]])   // non è lui l'autore del contenuto
                    ->orderBy([ContentLikes::tableName() . '.created_at' => SORT_DESC])
                    ->limit($module->contentsLimit);
                $res = $queryId->all();
                if ($debugMe) {
                    print 'B) contenuti like da $aboutUser che $toUser può vedere' . '<br />' . "\n";
                    //print $queryId->createCommand()->rawSql.'<br />'."\n";
                    print '$queryId - trovati ' . count($res) . '<br />' . "\n";
                } // $debugMe
                unset($queryId);
                if (count($res) > 0) {
                    $results['liked_by'][$classname] = $res;
                }
                
            } // foreach $modelsEnabled
            
        } // $cwhModule
        
        // D) ultimi utenti entrati in contatto con $aboutUser
        $queryId = UserProfileUtility::getQueryContacts($aboutUserId);
        $res = $queryId->all(); // tutti gli User connessi
        if ($debugMe) {
            print 'D.1) ultimi utenti entrati in contatto con $aboutUser' . '<br />' . "\n";
            //print $queryId->createCommand()->rawSql.'<br />'."\n";
            print '$queryId - trovati ' . count($res) . '<br />' . "\n";
        } // $debugMe
        if (count($res) > 0) {
            $uids = [];
            foreach ($res as $r) {
                $uids[] = $r->id;
            }
            
            $queryId = UserProfile::find()
                ->leftJoin(UserContact::tableName(), [
                    'or',
                    [
                        UserContact::tableName() . '.user_id' => new \yii\db\Expression(UserProfile::tableName() . '.user_id'),
                        UserContact::tableName() . '.contact_id' => $aboutUserId
                    ],
                    [
                        UserContact::tableName() . '.contact_id' => new \yii\db\Expression(UserProfile::tableName() . '.user_id'),
                        UserContact::tableName() . '.user_id' => $aboutUserId
                    ]
                ])
                ->where([UserProfile::tableName() . '.user_id' => $uids])
                ->andWhere([UserProfile::tableName() . '.attivo' => 1])
                ->orderBy([UserContact::tableName() . '.accepted_at' => SORT_DESC])
                ->limit($module->usersLimit);
            $res = $queryId->all();
            
            if ($debugMe) {
                print 'D.2) ultimi utenti entrati in contatto con $aboutUser' . '<br />' . "\n";
                //print $queryId->createCommand()->rawSql.'<br />'."\n";
                print '$queryId - trovati ' . count($res) . '<br />' . "\n";
            } // $debugMe
            
            if (count($res) > 0) {
                $results['connected_to']['open20\amos\admin\models\UserProfile'] = $res;
            }
            
        }
        
        if ($debugMe) {
            print 'findUserContentData - fine <br />' . "\n";//exit;
        } // $debugMe
        return $results;
    }
}
