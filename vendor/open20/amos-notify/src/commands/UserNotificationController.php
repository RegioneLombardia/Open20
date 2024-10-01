<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\commands
 * @category   CategoryName
 */
namespace open20\amos\notificationmanager\commands;

use open20\amos\admin\AmosAdmin;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\models\NotificationConf;
use open20\amos\notificationmanager\utility\NotifyUtility;
use open20\amos\notificationmanager\base\builder\AMailBuilder;
use open20\amos\notificationmanager\base\BuilderFactory;
use open20\amos\notificationmanager\models\Notification;
use open20\amos\notificationmanager\models\NotificationChannels;
use open20\amos\notificationmanager\models\NotificationsConfOpt;
use open20\amos\notificationmanager\models\base\NotificationSendEmail;
use open20\amos\notificationmanager\models\NotificationsSent;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\admin\models\UserContact;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use open20\amos\core\user\User;
use open20\amos\core\models\base\ContentLikes;
use open20\amos\core\models\base\ModelsClassname;
use open20\amos\comments\models\Comment;
use Yii;
use yii\db\Query;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\log\Logger;
use Exception;

/**
 * Class UserNotificationController
 * @package open20\amos\notificationmanager\controllers
 */
class UserNotificationController extends NotifierController
{
    
    
    /****
     * 5 - Successful User: il tuo profilo sta avendo successo
     */

    /**
     * This action sends periodic mails which shows the user successful contents.
     */
    public function actionSuccessfulUser ()
    {
        // TODO toglierlo
        $this->monthMails = true;
        //$this->dayMails = true;
        //$this->weekMails = true;
        
//        try {
            $type = $this->evaluateOperations();
            $allowedFrequency = array(
                NotificationsConfOpt::EMAIL_DAY,
                NotificationsConfOpt::EMAIL_WEEK,
                NotificationsConfOpt::EMAIL_MONTH                
            );
            if (!in_array($type, $allowedFrequency)) {
                Console::stdout('Error successful-user: not allowed frequency ' . $type . PHP_EOL);
                return;
            }
            
            Console::stdout('Begin successful-user ' . $type . PHP_EOL);
            $users = $this->loadUserEmailFrequency($type, 'profilo_successo_email');
            
            foreach ($users as $u) {
                Console::stdout($u['user_id'] . ') ' . $u['nome'] . ' ' . $u['cognome'] . ' email non c\'è ' . PHP_EOL);
            }

            $factory = new BuilderFactory();
            $builder = $factory->create(BuilderFactory::CONTENT_SUCCESSFUL_USER_BUILDER);

            $this->notifySuccessfulUserToUser($users, $builder, $type);

            Console::stdout('End successful-user ' . $type . PHP_EOL);
//        } catch (Exception $ex) {
//            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
//        }
    } //actionSuccessfulUser



    /**
     * @param AmosCwh|null $cwhModule
     * @param array $users
     * @param AMailBuilder $builder
     * @throws \yii\base\InvalidConfigException
     */
    private function notifySuccessfulUserToUser($users, $builder, $frequencyType = null)
    {
        $connection = \Yii::$app->db;
        $transaction = null;
        $cwhModule = Yii::$app->getModule('cwh');
       // try {
            foreach ($users as $user) {
                $transaction = $connection->beginTransaction();
                $uid = $user['user_id'];

                $notificationconf = NotificationConf::find()->andWhere(['user_id' => $uid])->one();
                Console::stdout(PHP_EOL. PHP_EOL.'notifySuccessfulUserToUser - Start working on user ' . $uid . PHP_EOL); //die();
                if (!empty($notificationconf)) {
                    
                    if (isset($cwhModule)) {
                        
                       $results = $this->findUserProfileNewView($uid, $frequencyType);

                    } // $cwhModule
                    
                    Console::stdout('notifySuccessfulUserToUser - prima di eseguire ' . PHP_EOL);//return;
                    if (!empty($results)) {
                        $builder->setFrequency($frequencyType);
                        $builder->sendEmailUserNotify([$uid], $results);
                        Console::stdout('Contents notified: base ' . count($results['base']) . PHP_EOL);
                    }

                    Console::stdout('End working on user ' . $uid . PHP_EOL); //die;

                    Console::stdout('---- ' . PHP_EOL);
                    $transaction->commit();
                    $transaction = null;
                    gc_collect_cycles();
                } // if $notificationconf
            } // foreach user
//        } catch (\Exception $e) {
//            if(!is_null($transaction))
//            {
//                $transaction->rollBack();
//            }
//            throw $e;
//        } catch (\Throwable $e) {
//            if(!is_null($transaction))
//            {
//                $transaction->rollBack();
//            }
//            throw $e;
//        }
    } // notifySuccessfulUserToUser


    
    protected function findUserProfileNewView($uid, $frequencyType) {
        
        $results = [];
        $debugMe = true;
        $module = AmosNotify::getInstance();

        
        // QUESTO E' TUTTO FINTO
        
        
        $queryId = $this->getQueryContacts($uid, /*$everyStatus*/ false, /*$limit*/ 0 /* , $notInList = null */);
        $res = $queryId->all(); // tutti gli User connessi
        if (count($res) > 0) {
            $contactsAcceptedUid = [];
            foreach ($res as $r) {
                $contactsAcceptedUid[] = $r->id;
            }
        }
        //pr($contactsAcceptedUid, '$contactsAcceptedUid'); //exit;

        if (count($contactsAcceptedUid) > 0) {
            $queryId = UserProfile::find()
                ->distinct()
                ->andWhere([UserProfile::tableName() . '.user_id' => $contactsAcceptedUid]);
            $howMany = $queryId->count();
            $results['view_profile_howmany'] = $howMany;
            
            
            $queryId->limit($module->usersLimit);
            $res = $queryId->all();
            if($debugMe) {
                Console::stdout('000) $contactsAcceptedUid'. PHP_EOL); //return;
                Console::stdout($queryId->createCommand()->rawSql. PHP_EOL); //exit;
                Console::stdout('$queryId - trovati ' . count($res) . ' su un totale di ' . $howMany . PHP_EOL); //return;
            } // $debugMe
            $results['view_profile']['open20\amos\admin\models\UserProfile'] = $res;
        }            

       
        // FINE QUESTO E' TUTTO FINTO
        
        return $results;
        
    } // findUserProfileNewView

    
    
    /****
     * 2 - Suggested Link: collegamenti suggeriti
     * 
     * This action sends periodic mails which suggests ther user interested in the same topics.
     */
    public function actionSuggestedLink ()
    {
        // TODO toglierlo
        $this->monthMails = true;
        //$this->dayMails = true;
        //$this->weekMails = true;
        
//        try {
            $type = $this->evaluateOperations();
            $allowedFrequency = array(
                NotificationsConfOpt::EMAIL_DAY,
                NotificationsConfOpt::EMAIL_WEEK,
                NotificationsConfOpt::EMAIL_MONTH                
            );
            if (!in_array($type, $allowedFrequency)) {
                Console::stdout('Error suggested-link: not allowed frequency ' . $type . PHP_EOL);
                return;
            }
            Console::stdout('Begin suggested-link ' . $type . PHP_EOL);
            $users = $this->loadUserEmailFrequency($type, 'contatti_suggeriti_email');
            
            foreach ($users as $u) {
                Console::stdout($u['user_id'] . ') ' . $u['nome'] . ' ' . $u['cognome'] . ' email non c\'è ' . PHP_EOL);
            }

            $factory = new BuilderFactory();
            $builder = $factory->create(BuilderFactory::CONTENT_SUGGESTED_LINK_BUILDER);

            $this->notifySuggestedLinkToUser($users, $builder, $type);

            Console::stdout('End suggested-link ' . $type . PHP_EOL);
//        } catch (Exception $ex) {
//            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
//        }
    } // actionSuggestedLink
    
    

    /**
     * @param AmosCwh|null $cwhModule
     * @param array $users
     * @param AMailBuilder $builder
     * @throws \yii\base\InvalidConfigException
     */
    private function notifySuggestedLinkToUser($users, $builder, $frequencyType = null)
    {
        $connection = \Yii::$app->db;
        $transaction = null;
        $cwhModule = Yii::$app->getModule('cwh');
       // try {
            foreach ($users as $user) {
                $transaction = $connection->beginTransaction();
                $uid = $user['user_id'];

                $notificationconf = NotificationConf::find()->andWhere(['user_id' => $uid])->one();
                Console::stdout(PHP_EOL. PHP_EOL.'notifySuggestedLinkToUser - Start working on user ' . $uid . PHP_EOL); //die();
                if (!empty($notificationconf)) {
                    
                    if (isset($cwhModule)) {
                        
                        //list($results, $resultsHowMany) = $this->findUserContentRead($uid, $frequencyType);
                        $results = $this->findUserNewContacts($uid, $frequencyType);

                    } // $cwhModule
                    
                    Console::stdout('notifySuggestedLinkToUser - prima di eseguire ' . PHP_EOL);
                    
                        Console::stdout('Suggested Link ' . count($results) . PHP_EOL);
                    // se c'è contenuto manda la mail giusta e poi scrive su read che quei contenuti glieli ha già notificati
                    if (!empty($results)) {
                        $builder->sendEmailUserNotify([$uid], $results);
                        Console::stdout('Suggested Link groups ' . count($results) . PHP_EOL);
                    }

                    Console::stdout('End working on user ' . $uid . PHP_EOL); //die;

                    Console::stdout('---- ' . PHP_EOL);
                    $transaction->commit();
                    $transaction = null;
                    gc_collect_cycles();
                } // if $notificationconf
            } // foreach user
//        } catch (\Exception $e) {
//            if(!is_null($transaction))
//            {
//                $transaction->rollBack();
//            }
//            throw $e;
//        } catch (\Throwable $e) {
//            if(!is_null($transaction))
//            {
//                $transaction->rollBack();
//            }
//            throw $e;
//        }
    } // notifySuggestedLinkToUser

    
    protected function findUserNewContacts($uid, $frequencyType) {
        
        $results = [];
        $debugMe = false;
        $module = AmosNotify::getInstance();

        // 0.1 - tutti gli utenti già connessi (dei quali cerco i contatti)
        //$queryId = UserProfileUtility::getQueryContacts($uid);
        $queryId = $this->getQueryContacts($uid, /*$everyStatus*/ false, /*$limit*/ 0 /* , $notInList = null */);
        $res = $queryId->all(); // tutti gli User connessi
        if($debugMe) {
            Console::stdout('0.1) $contactsAcceptedUid'. PHP_EOL); //return;
            Console::stdout($queryId->createCommand()->rawSql. PHP_EOL); //exit;
            Console::stdout('$queryId - trovati ' . count($res) . PHP_EOL); //return;
        } // $debugMe
        if (count($res) > 0) {
            $contactsAcceptedUid = [];
            foreach ($res as $r) {
                $contactsAcceptedUid[] = $r->id;
            }
        }
        //pr($contactsAcceptedUid, '$contactsAcceptedUid'); //exit;
        
        // 0.2 - tutti gli utenti con cui ha già provato un contatto anche REFUSED e INVITED
        $queryId = $this->getQueryContacts($uid, /*$everyStatus*/ true, /*$limit*/ 0 /* , $notInList = null */);
        $res = $queryId->all(); // tutti gli User connessi
        if($debugMe) {
            Console::stdout('0.2) $uidsAlreadyIn'. PHP_EOL); //return;
            Console::stdout($queryId->createCommand()->rawSql. PHP_EOL); //exit;
            Console::stdout('$queryId - trovati ' . count($res) . PHP_EOL); //return;
        } // $debugMe
        if (count($res) > 0) {
            $uidsAlreadyIn = [];
            foreach ($res as $r) {
                $uidsAlreadyIn[] = $r->id;
            }
        }
        $uidsAlreadyIn[] = $uid;    // non vogliamo che venga fuori neppure lui
        //pr($uidsAlreadyIn, '$uidsAlreadyIn'); //exit;


        
        // A Utenti nelle tue community con almeno un tag che combacia
        $res = $this->findUserNewContactsCommunityA($uid, $uidsAlreadyIn, $debugMe);
        if (count($res) > 0) {
            $results['community']['open20\amos\admin\models\UserProfile'] = $res;
        }            
 

        
        // B Utenti interessati ai contenuti - hanno commentato
        $res = $this->findUserNewContactsCommentsB($uid, $uidsAlreadyIn, $debugMe);
        if (count($res) > 0) {
            $results['comments']['open20\amos\admin\models\UserProfile'] = $res;
        }            


        
        // C Utenti nella rete dei miei contatti
        // per evitare che siano sempre gli stessi, riordino casualmente l'array dei suoi contatti e ne prendo solo 3 tra cui cercare altri contatti
        shuffle($contactsAcceptedUid);
        $queryId = $this->getQueryContacts(array_slice($contactsAcceptedUid, 0, 3), /*$everyStatus*/ true, /*$limit*/ $module->usersLimit , /* $notInList */$uidsAlreadyIn);
        // messo qui non lo inserisce nella query forse perché è una UNION DISTINCT $queryId->limit($module->usersLimit);
        $res = $queryId->all(); // tutti gli User connessi
        if($debugMe) {                
            Console::stdout('C.1) Gli user amici dei miei amici'. PHP_EOL); //return;
            Console::stdout($queryId->createCommand()->rawSql. PHP_EOL); //return;
            Console::stdout('$queryId - trovati ' . count($res) . PHP_EOL); //return;
        }

        if (count($res) > 0) {
            $contactUserIds = [];
            foreach($res as $r) {
                $contactUserIds[] = $r['id'];
            }
            $queryId = UserProfile::find()
                ->distinct()
                ->andWhere([UserProfile::tableName() . '.user_id' => $contactUserIds])
                ->limit($module->usersLimit);                
            $res = $queryId->all();
            $results['network']['open20\amos\admin\models\UserProfile'] = $res;
        }            


        
        // D Utenti in una delle mie organizzazioni
        $res = $this->findUserNewContactsOrganizzationD($uid, $uidsAlreadyIn, $debugMe);
        if (count($res) > 0) {
            $results['organizations']['open20\amos\admin\models\UserProfile'] = $res;
        }            
        
        return $results;
        
    } // findUserNewContacts


    protected function findUserNewContactsOrganizzationD($uid, $uidsAlreadyIn, $debugMe) {
    
        $moduleOrganizzation = Yii::$app->getModule('organizations');
        $module = AmosNotify::getInstance();
        if ($moduleOrganizzation) {
            $moClass = $moduleOrganizzation->getOrganizationModelClass();
            $isAmosOrganizzazioni = ($moClass == 'open20\amos\organizzazioni\models\Profilo');
            $isOiOrganizations = ($moClass == 'openinnovation\organizations\models\Organizations');
            if (!$isAmosOrganizzazioni && !$isOiOrganizations) {
                Console::stdout('Error: organizzation '. $moClass . ' is not managed.' . PHP_EOL); //return;
                return []; // funziona solo con quei due
            }
            $moMMtable = ($isAmosOrganizzazioni) ? \open20\amos\organizzazioni\models\ProfiloUserMm::tableName() 
                                                 : \openinnovation\organizations\models\OrganizationsUserMm::tableName();
            $moOrganizzationRelField = ($isAmosOrganizzazioni) ? 'profilo_id' : 'organization_id';
            $moUserRelField = 'user_id';
            //Console::stdout('D) $isAmosOrganizzazioni? '. $isAmosOrganizzazioni . '; $isOiOrganizations? '. $isOiOrganizations . PHP_EOL); //return;
            
            $myOrganizzations = $moduleOrganizzation->getUserOrganizations($uid);            
            $myOrganizzationsIds = [];
            foreach($myOrganizzations as $o) {
                $myOrganizzationsIds[] = $o->id;
            }
            //pr($myOrganizzationsIds, '$myOrganizzationsIds');
            
            if(count($myOrganizzationsIds)) {
                $queryId = UserProfile::find()
                    ->innerJoin($moMMtable, [
                        $moMMtable . '.' . $moUserRelField => new \yii\db\Expression(UserProfile::tableName() . '.user_id'),
                        $moMMtable . '.' . $moOrganizzationRelField => $myOrganizzationsIds
                        ])
                    ->distinct()
                    ->andWhere([UserProfile::tableName() . '.attivo' => 1])
                    ->andWhere(['not', [UserProfile::tableName() . '.user_id' => $uidsAlreadyIn]])
                    ->limit($module->usersLimit);                
                $res = $queryId->all();
            
                if($debugMe) {                
                    Console::stdout('D) $queryId'. PHP_EOL); //return;
                    Console::stdout($queryId->createCommand()->rawSql. PHP_EOL); //return;
                    Console::stdout('$queryId - trovati ' . count($res) . PHP_EOL); //return;
                }
                return $res;
            }
        } // $moduleOrganizzation

        return [];
        
    } // findUserNewContactsOrganizzationD
    
    
        
    protected function findUserNewContactsCommentsB($uid, $uidsAlreadyIn, $debugMe) {
        
        $idsUsers = [];
        $module = AmosNotify::getInstance();
        $modelsEnabled = \open20\amos\cwh\models\CwhConfigContents::find()->addSelect('classname')->column();
        foreach ($modelsEnabled as $classname) {
            //Console::stdout('findUserNewContacts - $modelsEnabled $classname ' . $classname . PHP_EOL);
            $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery($classname, [
                'queryBase' => $classname::find(),
                'userId' => $uid
            ]);
            $cwhActiveQuery::$userProfile = null; //reset user profile

            $res = [];
            
            // B.1) trovo gli id degli user che hanno fatto almeno un commento ai contenuti di questo autore della classe $classname
            $queryId = $cwhActiveQuery->getQueryCwhOwnInterest();
            $queryId->innerJoin(Comment::tableName(), [
                    $classname::tableName() . '.id' => new \yii\db\Expression(Comment::tableName() . '.context_id'),
                    Comment::tableName() . '.context' => $classname
                    ])
                ->distinct()
                ->select(Comment::tableName() . '.created_by')
                ->andWhere([
                    $classname::tableName() . '.created_by' => $uid,
                    Comment::tableName() . '.deleted_at' => null])
                    ->andWhere(['not', [Comment::tableName() . '.created_by' => $uidsAlreadyIn]])
                ->orderBy([Comment::tableName() . '.updated_at' => SORT_DESC])
                ->limit($module->usersLimit);
            $res = $queryId->all();

            if($debugMe) {                
                Console::stdout('B.1) Id User che hanno commentato ' . $classname . PHP_EOL); //return;
                Console::stdout($queryId->createCommand()->rawSql. PHP_EOL); //return;
                Console::stdout('$queryId - trovati ' . count($res) . PHP_EOL); //return;
            } // $debugMe
            unset($queryId);
            if (count($res) == 0) {
                continue;
            }
            $ids = [];
            foreach ($res as $r) {
                $ids[] = $r['created_by'];
                if (!in_array($r['created_by'], $idsUsers)) {
                    $idsUsers[] = $r['created_by'];
                }
            }
            //pr($ids, '$ids '.$classname);
        } // foreach $modelsEnabled        
        
        
        //pr($idsUsers, '$idsUsers');
        
        $res = [];
        if(count($idsUsers)) {
            $queryId = UserProfile::find()
                ->where([UserProfile::tableName() . '.user_id' => array_slice($idsUsers, 0, $module->usersLimit)])
                ->andWhere([UserProfile::tableName() . '.attivo' => 1])
                ->limit($module->usersLimit);                
            $res = $queryId->all();

            if($debugMe) {                
                Console::stdout('B.2) UserProfile di coloro che hanno commentato'. PHP_EOL); //return;
                Console::stdout($queryId->createCommand()->rawSql. PHP_EOL); //return;
                Console::stdout('$queryId - trovati ' . count($res) . PHP_EOL); //return;
            }
        }
        return $res;
        
    } // findUserNewContactsCommentsB
    
    
    protected function findUserNewContactsCommunityA($uid, $uidsAlreadyIn, $debugMe) {
    
        $moduleCommunity = Yii::$app->getModule('community');
        $module = AmosNotify::getInstance();
        if ($moduleCommunity) {
            $myCommunityIds = $moduleCommunity->getCommunitiesByUserId($uid, /*$onlyIds*/ true);
            //pr($myCommunityIds, '$myCommunityIds'); //exit;
            $queryId = CwhTagOwnerInterestMm::find()
                    ->select(CwhTagOwnerInterestMm::tableName() . '.tag_id')
                    ->distinct()
                    ->where(['classname' => 'open20\amos\admin\models\UserProfile'])
                    ->andWhere(['record_id' => $uid]);
            $myTagsIds = $queryId->column();
            //pr($myTagsIds, '$myTagsIds'); //exit;
            if($debugMe) {                
                Console::stdout('A.1) $myTagsIds'. PHP_EOL); //return;
                Console::stdout($queryId->createCommand()->rawSql. PHP_EOL); //return;
                Console::stdout('$queryId - trovati ' . count($myTagsIds) . PHP_EOL); //return;
            }
            
            $queryId = UserProfile::find()
                    ->distinct()
                    ->innerJoin(CommunityUserMm::tableName(), [
                        CommunityUserMm::tableName() . '.status'  => CommunityUserMm::STATUS_ACTIVE,
                        CommunityUserMm::tableName() . '.community_id'  => $myCommunityIds,
                        CommunityUserMm::tableName() . '.user_id'  => new \yii\db\Expression(UserProfile::tableName() . '.user_id')
                    ])
                    ->innerJoin(CwhTagOwnerInterestMm::tableName(), [
                        CwhTagOwnerInterestMm::tableName() . '.classname' => 'open20\amos\admin\models\UserProfile',
                        CwhTagOwnerInterestMm::tableName() . '.record_id'  => new \yii\db\Expression(UserProfile::tableName() . '.id'),
                        CwhTagOwnerInterestMm::tableName() . '.tag_id'  => $myTagsIds,
                    ])
                    ->where(['not', [
                        CommunityUserMm::tableName() . '.user_id'  => $uidsAlreadyIn                   
                    ]])
                    ->andWhere([UserProfile::tableName() . '.attivo' => 1])
                    ->limit($module->usersLimit);
            $res = $queryId->all();
            if($debugMe) {                
                Console::stdout('A.2) ContactsCommunity'. PHP_EOL); //return;
                Console::stdout($queryId->createCommand()->rawSql. PHP_EOL); //return;
                Console::stdout('$queryId - trovati ' . count($res) . PHP_EOL); //return;
            }
            
            return $res;
        } // $moduleCommunity

        return [];
        
    } // findUserNewContactsCommunityA
    

    /**
     *
     * Torna i contatti anche di tutti gli stati e per una lista di id
     */
    private static function getQueryContacts($userId, $everyStatus = false, $limit = 0, $notInList = null)
    {
        $contactsInvited =
            User::find()
                ->innerJoin('user_contact', 'user.id = user_contact.contact_id')
                ->innerJoin('user_profile', 'user_profile.user_id = user.id')
                ->andWhere('user_contact.deleted_at IS NULL AND user_profile.deleted_at IS NULL')
                ->andWhere(["user_contact.user_id" => $userId])
                ->andWhere(['attivo' => 1]);

        $contactsInviting =
            User::find()
                ->innerJoin('user_contact', 'user.id = user_contact.user_id')
                ->innerJoin('user_profile', 'user_profile.user_id = user.id')
                ->andWhere('user_contact.deleted_at IS NULL AND user_profile.deleted_at IS NULL')
                ->andWhere(["user_contact.contact_id" => $userId])
                ->andWhere(['attivo' => 1]);
        
        if(!$everyStatus) {
            $contactsInvited->andWhere(['user_contact.status' => UserContact::STATUS_ACCEPTED]);
            $contactsInviting->andWhere(['user_contact.status' => UserContact::STATUS_ACCEPTED]);
        }
        if ($limit) {
            $contactsInvited->limit($limit);
            $contactsInviting->limit($limit);
        }
        if (!is_null($notInList)) {
            $contactsInvited->andWhere(['not', ["user_contact.contact_id" => $notInList]]);
            $contactsInviting->andWhere(['not', ["user_contact.user_id" => $notInList]]);
        }
        return $contactsInvited->union($contactsInviting);
    }


    
    /****
     * 4 - Successful Content: il tuo contenuto sta avendo successo
     */

    /**
     * This action sends periodic mails which shows the user successful contents.
     */
    public function actionSuccessfulContent ()
    {
        // TODO toglierlo
        $this->monthMails = true;
        //$this->dayMails = true;
        //$this->weekMails = true;
        
//        try {
            $type = $this->evaluateOperations();
            $allowedFrequency = array(
                NotificationsConfOpt::EMAIL_DAY,
                NotificationsConfOpt::EMAIL_WEEK,
                NotificationsConfOpt::EMAIL_MONTH                
            );
            if (!in_array($type, $allowedFrequency)) {
                Console::stdout('Error successful-content: not allowed frequency ' . $type . PHP_EOL);
                return;
            }
            
            Console::stdout('Begin successful-content ' . $type . PHP_EOL);
            $users = $this->loadUserEmailFrequency($type, 'contenuti_successo_email');
            
            foreach ($users as $u) {
                Console::stdout($u['user_id'] . ') ' . $u['nome'] . ' ' . $u['cognome'] . ' email non c\'è ' . PHP_EOL);
            }

            $factory = new BuilderFactory();
            $builder = $factory->create(BuilderFactory::CONTENT_SUCCESSFUL_CONTENT_BUILDER);

            $this->notifySuccessfulContentsToUser($users, $builder, $type);

            Console::stdout('End successful-content ' . $type . PHP_EOL);
//        } catch (Exception $ex) {
//            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
//        }
    }
    

    /**
     * @param AmosCwh|null $cwhModule
     * @param array $users
     * @param AMailBuilder $builder
     * @throws \yii\base\InvalidConfigException
     */
    private function notifySuccessfulContentsToUser($users, $builder, $frequencyType = null)
    {
        $connection = \Yii::$app->db;
        $transaction = null;
        $cwhModule = Yii::$app->getModule('cwh');
       // try {
            foreach ($users as $user) {
                $transaction = $connection->beginTransaction();
                $uid = $user['user_id'];

                $notificationconf = NotificationConf::find()->andWhere(['user_id' => $uid])->one();
                Console::stdout(PHP_EOL. PHP_EOL.'notifySuccessfulContentsToUser - Start working on user ' . $uid . PHP_EOL); //die();
                if (!empty($notificationconf)) {
                    
                    if (isset($cwhModule)) {
                        
                       $results = $this->findUserContentRead($uid, $frequencyType);

                    } // $cwhModule
                    
                    Console::stdout('notifySuccessfulContentsToUser - prima di eseguire ' . PHP_EOL);//return;
                    if (!empty($results)) {
                        $builder->setFrequency($frequencyType);
                        $builder->sendEmailUserNotify([$uid], $results);
                        Console::stdout('Contents notified: base ' . count($results['base']) . PHP_EOL);
                    }

                    Console::stdout('End working on user ' . $uid . PHP_EOL); //die;

                    Console::stdout('---- ' . PHP_EOL);
                    $transaction->commit();
                    $transaction = null;
                    gc_collect_cycles();
                } // if $notificationconf
            } // foreach user
//        } catch (\Exception $e) {
//            if(!is_null($transaction))
//            {
//                $transaction->rollBack();
//            }
//            throw $e;
//        } catch (\Throwable $e) {
//            if(!is_null($transaction))
//            {
//                $transaction->rollBack();
//            }
//            throw $e;
//        }
    }


    protected function findUserContentRead($uid, $frequencyType) {
        
        $beforeTimestamp = time() - $this->frequencyDeltaTimestamp($frequencyType); // creato non prima di ...
        $results = [];
        $debugMe = false;
        $idsCountsGlobal = [];
        $module = AmosNotify::getInstance();
        
        $modelsEnabled = \open20\amos\cwh\models\CwhConfigContents::find()->addSelect('classname')->column();
        foreach ($modelsEnabled as $classname) {
            Console::stdout('findUserContentRead - $modelsEnabled $classname ' . $classname . PHP_EOL);
            $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery($classname, [
                'queryBase' => $classname::find(),
                'userId' => $uid
            ]);
            $cwhActiveQuery::$userProfile = null; //reset user profile

            $queryId = $cwhActiveQuery->getQueryCwhOwnInterest();
            $queryId->andWhere(['created_by' => $uid]);

            // A) trovo gli id dei contenuti di questo autore
            $queryId
                    ->select($classname::tableName() . '.id')
                    ->distinct();
            $res = $queryId->all(); // id distinct contents
            if($debugMe) {                
                Console::stdout('A) $queryId'. PHP_EOL); //return;
                Console::stdout($queryId->createCommand()->rawSql. PHP_EOL); //return;
                Console::stdout('$queryId - trovati ' . count($res) . PHP_EOL); //return;
            } // $debugMe
            unset($queryId);
            if (count($res) == 0) {
                continue;
            }
            $ids = [];
            $idsCounts = [];
            foreach ($res as $r) {
                $ids[] = $r->id;
            }

            // B) trovo i contenuti col maggior numero di letture
            $queryHowMany = (new Query())
                ->select([Notification::tableName() . '.content_id','COUNT(DISTINCT '.Notification::tableName() . '.user_id'.') as howmany'])
                ->from(Notification::tableName())
                ->where([
                    Notification::tableName() . '.class_name' => $classname,
                    Notification::tableName() . '.channels' => [NotificationChannels::CHANNEL_READ,NotificationChannels::CHANNEL_READ_DETAIL],
                    Notification::tableName() . '.content_id' => $ids
                ])
                ->andWhere(['<', new \yii\db\Expression("UNIX_TIMESTAMP(" . Notification::tableName() . '.created_at' . ")"), $beforeTimestamp]) // solo quelli degli ultimi frequency giorni
                ->groupBy(Notification::tableName() . '.content_id')
                // lo fa solo dopo ->limit($module->contentsLimit)
                ->orderBy(['howmany' => SORT_DESC]);
                
            $res = $queryHowMany->all();
            if($debugMe) {                
                Console::stdout('B) $queryHowMany'. PHP_EOL); //return;
                Console::stdout($queryHowMany->createCommand()->rawSql. PHP_EOL); //return;
                Console::stdout('$queryHowMany - trovati ' . count($res) . PHP_EOL); //return;
            } // $debugMe
            unset($queryHowMany);
            $readHMCount = count($res); 
            if ($readHMCount) {
                $ids_read = [];
                foreach ($res as $r) {
                    //Console::stdout('HowMany - '.$r['content_id'].': '. $r['howmany'] . PHP_EOL); //return;
                    $results['read']['resultsHowMany'][$classname][$r['content_id']] = $r['howmany'];
                    $ids_read[] = $r['content_id'];
                    $idsCounts[$r['content_id']]['read'] = $r['howmany'];
                }
                //pr($results['read']['resultsHowMany'], '$resultsHowMany (read)');//die();
            } // $readHMCount
            

            // D) trovo i contenuti col maggior numero di commenti
            $queryHowMany = (new Query())
                ->select([Comment::tableName() . '.context',Comment::tableName() . '.context_id','COUNT(DISTINCT '.Notification::tableName() . '.user_id'.') as howmany'])
                ->from(Notification::tableName())
                ->innerJoin(Comment::tableName(), [
                    Notification::tableName() . '.content_id' => new \yii\db\Expression(Comment::tableName() . '.id'),
                    Comment::tableName() . '.context' => $classname
                    ])
                ->where([
                    Notification::tableName() . '.class_name' => \open20\amos\comments\models\Comment::className(),
                    Notification::tableName() . '.channels' => NotificationChannels::CHANNEL_MAIL
                ])
                ->andWhere([Comment::tableName() . '.context_id' => $ids])
                ->andWhere(['>=', Notification::tableName() . '.created_at', $beforeTimestamp]) // solo quelli degli ultimi frequency giorni
                ->groupBy(Notification::tableName() . '.content_id')
                // lo fa solo dopo ->limit($module->contentsLimit)
                ->orderBy(['howmany' => SORT_DESC]);
            $res = $queryHowMany->all();
            if($debugMe) {                
                Console::stdout('D) $queryHowMany'. PHP_EOL); //return;
                Console::stdout($queryHowMany->createCommand()->rawSql. PHP_EOL); //return;
                Console::stdout('$queryHowMany - trovati ' . count($res) . PHP_EOL); //return;
            } // $debugMe
            unset($queryHowMany);
            
            $commentsHMCount = count($res); 
            if ($commentsHMCount) {
                $ids_comments = [];
                foreach ($res as $r) {
                    //Console::stdout('HowMany - '.$r['context_id'].': '. $r['howmany'] . PHP_EOL); //return;
                    $results['comments']['resultsHowMany'][$r['context']][$r['context_id']] = $r['howmany'];
                    $ids_comments[] = $r['context_id'];
                    $idsCounts[$r['context_id']]['comments'] = $r['howmany'];
                }
                //pr($results['comments']['resultsHowMany'], '$resultsHowMany (comment)');die();
                //pr($ids_comments, '$ids_comments');die();
            } // $commentsHMCount


            // F) trovo i contenuti col maggior numero di like
            $queryHowMany = (new Query())
                ->select([ModelsClassname::tableName() . '.classname',ContentLikes::tableName() . '.content_id','COUNT(DISTINCT '.ContentLikes::tableName() . '.user_id'.') as howmany'])
                ->from(ContentLikes::tableName())
                ->innerJoin(ModelsClassname::tableName(), [
                    ModelsClassname::tableName() . '.id' => new \yii\db\Expression(ContentLikes::tableName() . '.models_classname_id'),
                    ModelsClassname::tableName() . '.classname' => $classname
                    ])
                ->where([ContentLikes::tableName() . '.likes' => 1])
                ->andWhere([ContentLikes::tableName() . '.content_id' => $ids])
                ->andWhere(['>=', new \yii\db\Expression("UNIX_TIMESTAMP(" . ContentLikes::tableName() . '.created_at' . ")"), $beforeTimestamp]) // solo quelli degli ultimi frequency giorni
                ->groupBy(ContentLikes::tableName() . '.content_id')
                // lo fa solo dopo ->limit($module->contentsLimit)
                ->orderBy(['howmany' => SORT_DESC]);
            $res = $queryHowMany->all();
            if($debugMe) {                
                Console::stdout('F) $queryHowMany'. PHP_EOL); //return;
                Console::stdout($queryHowMany->createCommand()->rawSql. PHP_EOL); //return;
                Console::stdout('$queryHowMany - trovati ' . count($res) . PHP_EOL); //return;
            } // $debugMe
            unset($queryHowMany);

            
            $likesHMCount = count($res); 
            if ($likesHMCount) {
                $ids_likes = [];
                foreach ($res as $r) {
                    //Console::stdout('HowMany - '.$r['content_id'].': '. $r['howmany'] . PHP_EOL); //return;
                    $results['likes']['resultsHowMany'][$r['classname']][$r['content_id']] = $r['howmany'];
                    $ids_likes[] = $r['content_id'];
                    $idsCounts[$r['content_id']]['likes'] = $r['howmany'];
                }
                //pr($results['likes']['resultsHowMany'], '$resultsHowMany (like)');//die();
                //pr($ids_likes, '$ids_likes');die();
            }
            

            $idToGet = $this->getTheContentOrdered($idsCounts);
            if (!is_null($idToGet)) {
                //$idsCountsGlobal[$classname] = current($idToGet);
                //pr($idsCounts, '$idsCounts');//exit;
                //pr($idToGet, '$idToGet');exit;
                //pr($idsCountsGlobal, '$idsCountsGlobal');exit;
            }
            
            
            
            if (true) {
                // H) prendo solo i contenuti col maggior numero di read+like+contents
                $queryModel = $cwhActiveQuery->getQueryCwhOwnInterest();
                $queryModel
                        ->andWhere([$classname::tableName() . '.id' => array_keys($idToGet)])
                        ->limit($module->contentsLimit)
                        ->orderBy([$classname::tableName() . '.id' => SORT_DESC]);

                $res = $queryModel->all();
                if (count($res)) {
                    $results['base'][$classname] = $res;
                    $results['idsCounts'][$classname] = $idsCounts;
                    $results['idsCountsGlobal'][$classname] = key($idToGet);
                }

                if($debugMe) {                
                    Console::stdout('H) $queryModel' . $classname . PHP_EOL);
                    Console::stdout($queryModel->createCommand()->rawSql. PHP_EOL); //return;
                    Console::stdout( '$queryModel trovati: '.count($results['base'][$classname]). PHP_EOL); //return;
                } // $debugMe
                unset($queryModel);
            } // contenuti con read, like e contents            
            
            unset($cwhActiveQuery);           
        } // foreach $modelsEnabled        
        
         //pr($idsCountsGlobal, '$idsCountsGlobal');//exit;
         return $results;
        
    } // findUserContentRead


    private function getTheContentOrdered($idsCounts) {

        $_idCountsSum = [];
        foreach($idsCounts as $id => $v) {
            $_idCountsSum[$id] = $v['read'] + $v['comments'] + $v['likes'];
        }
        arsort($_idCountsSum);
        
        //pr($_idCountsSum, '$_idCountsSum');
        return array_slice($_idCountsSum, 0, 1, true);
        
    }
    

     /****
     * 3 - Sleeping User: recupero utenti dormienti
     */
    
    /**
     * This action sends nightly mails.
     */
    public function actionSleepingUser ()
    {
//        try {
            $type = $this->evaluateOperations();
            Console::stdout('Begin sleeping-user ' . PHP_EOL);
            $users = $this->loadUserBooleanFlag($type, 'periodo_inattivita_flag', 'sleeping-user');
            
            foreach ($users as $u) {
                Console::stdout($u['nome'] . ' ' . $u['cognome'] . ' email non c\'è ' . PHP_EOL);
            }

            $factory = new BuilderFactory();
            $builder = $factory->create(BuilderFactory::CONTENT_SLEEPING_USER_BUILDER);

            $this->notifyToSleepingUsers($users, $builder, $type);
            Console::stdout('End sleeping-user ' . PHP_EOL);
//        } catch (Exception $ex) {
//            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
//        }
    }
    
    /**
     * @param AmosCwh|null $cwhModule
     * @param array $users
     * @param AMailBuilder $builder
     * @throws \yii\base\InvalidConfigException
     */
    private function notifyToSleepingUsers($users, $builder, $frequencyType = null)
    {
        $connection = \Yii::$app->db;
        $transaction = null;
        $results = [];
        $cwhModule = Yii::$app->getModule('cwh');
        $module = AmosNotify::getInstance();
       // try {
            foreach ($users as $user) {
                $transaction = $connection->beginTransaction();
                $uid = $user['user_id'];

                $notificationconf = NotificationConf::find()->andWhere(['user_id' => $uid])->one();
                Console::stdout('Start working on user ' . $uid . PHP_EOL);
                if (!empty($notificationconf)) {
                    
                    if (isset($cwhModule)) {
                        $modelsEnabled = \open20\amos\cwh\models\CwhConfigContents::find()->addSelect('classname')->column();
                        $andWhere = "";
                        $i = 0;
                        foreach ($modelsEnabled as $classname) {
                            //Console::stdout('notifyToSleepingUsers - $modelsEnabled $classname ' . $classname . PHP_EOL);
                            $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery($classname, [
                                'queryBase' => $classname::find(),
                                'userId' => $uid
                            ]);
                            $cwhActiveQuery::$userProfile = null; //reset user profile

                            $queryModel = $cwhActiveQuery->getQueryCwhOwnInterest();
                            $queryModel->limit($module->contentsLimit);
                            $queryModel->orderBy([$classname::tableName() . '.id' => SORT_DESC]);
                            
                            $res = $queryModel->all();
                            if (count($res)) {
                                $results[$classname] = $res;
                            }
                                    
                            //Console::stdout('$cwhActiveQuery->getQueryCwhOwnInterest()' . $classname . PHP_EOL);
                            //Console::stdout($queryModel->createCommand()->rawSql. PHP_EOL); //return;
                            //Console::stdout( 'trovati: '.count($results[$classname]). PHP_EOL); //return;

                            unset($cwhActiveQuery);
                            unset($queryModel);
                        }
                    } // $cwhModule
                    
                    Console::stdout('notifyToSleepingUsers - prima di eseguire ' . PHP_EOL);
                    
                    // se c'è contenuto manda la mail giusta e poi scrive su read che quei contenuti glieli ha già notificati
                    if (!empty($results)) {
                        $builder->sendEmailUserNotify([$uid], $results);
                        Console::stdout('Contents notified: ' . (count($results['normal']) + count($results['network'])) . PHP_EOL);
                        $this->notifySentOnlyLast(NotificationsSent::SLEEPING_USER, $uid);
                    }

                    Console::stdout('End working on user ' . $uid . PHP_EOL);
                    
                    Console::stdout('---- ' . PHP_EOL);
                    $transaction->commit();
                    $transaction = null;
                    gc_collect_cycles();
                } 
            }
//        } catch (\Exception $e) {
//            if(!is_null($transaction))
//            {
//                $transaction->rollBack();
//            }
//            throw $e;
//        } catch (\Throwable $e) {
//            if(!is_null($transaction))
//            {
//                $transaction->rollBack();
//            }
//            throw $e;
//        }
    }

    
    /**
     * UserProfile ij User lj NotificationConf
     * Check on active user with notifications_enabled null o true (the same as NotifierController)
     * 
     * @param $schedule
     * @return array|null
     */
    protected function getBaseUserQuery($schedule)
    {
        $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());   /** @var AmosAdmin $adminModule */

        $query = new Query();
        $query->from(UserProfile::tableName());
        $query->innerJoin(User::tableName(),
            UserProfile::tableName() . '.user_id = ' . User::tableName() . '.id');
        $query->leftJoin(NotificationConf::tableName(),
            NotificationConf::tableName() . '.user_id = ' . UserProfile::tableName() . '.user_id');
                    $query->andWhere(['OR',
            [NotificationConf::tableName() . '.notifications_enabled' => 1],
            [NotificationConf::tableName() . '.notifications_enabled' => NULL ],
            ]);
        $query->andWhere([UserProfile::tableName() . '.deleted_at' => null]);
        $query->andWhere([UserProfile::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $query->andWhere([User::tableName() . '.status' => User::STATUS_ACTIVE]);
        if (
            $adminModule->confManager->isVisibleBox('box_privacy', ConfigurationManager::VIEW_TYPE_FORM) &&
            $adminModule->confManager->isVisibleField('privacy', ConfigurationManager::VIEW_TYPE_FORM)
        ) {
            $query->andWhere([UserProfile::tableName() . '.privacy' => 1]);
        }
        //Console::stdout($query->createCommand()->rawSql . PHP_EOL);

        return $query;

    } // getBaseUserQuery

    
    /**
     * @param $schedule
     * @return array|null
     */
    protected function loadUserEmailFrequency($schedule, $emailFrequencyField)
    {
        $result = null;
        try {
            $module = AmosNotify::getInstance();

            $query = $this->getBaseUserQuery($schedule);

            // filter the query for type of cron
            if ($schedule == $module->defaultSchedule) {
                $query->andWhere(['or',
                    [NotificationConf::tableName() . '.' . $emailFrequencyField => $schedule],
                    [NotificationConf::tableName() . '.' . $emailFrequencyField => null]
                ]);
            } else {
                $query->andWhere([NotificationConf::tableName() . '.' . $emailFrequencyField => $schedule]);
            }

            $query->orderBy([UserProfile::tableName() . '.user_id' => SORT_ASC]);
            $query->select(UserProfile::tableName() . '.*');

            Console::stdout($query->createCommand()->rawSql . PHP_EOL);

            $result = $query->all();
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        return $result;
    }

    
    /**
     * @param $schedule
     * @return array|null
     */
    protected function loadUserBooleanFlag($schedule, $flagField, $addCond = null)
    {
        $result = null;
        try {
            $module = AmosNotify::getInstance();

            $query = $this->getBaseUserQuery($schedule);

            // filter the query for type of cron
            if ($schedule == $module->defaultSchedule) {
                $query->andWhere(['or',
                    [NotificationConf::tableName() . '.' . $flagField => true],
                    [NotificationConf::tableName() . '.' . $flagField => null]
                ]);
            } else {
                $query->andWhere([NotificationConf::tableName() . '.' . $flagField => true]);
            }

            if(isset($addCond) && ($addCond == 'sleeping-user')) {
                $notify = AmosNotify::instance();
                $deltaTimestamp = 86400 * $notify->sleepingUserDayLimit;
                $nowTimestamp = time();
                $query->leftJoin(NotificationsSent::tableName(), [
                                        NotificationsSent::tableName() . '.type' => NotificationsSent::SLEEPING_USER, 
                                        NotificationsSent::tableName() . '.user_id' => new \yii\db\Expression(UserProfile::tableName() . '.user_id')
                                ])
                        ->andWhere(['not', [UserProfile::tableName() . '.ultimo_accesso' => null]]) // deve aver fatto almeno un accesso o un logout
                        ->andWhere(['not', [UserProfile::tableName() . '.ultimo_logout ' => null]])
                        ->andWhere(['<', new \yii\db\Expression("UNIX_TIMESTAMP(" . UserProfile::tableName() . '.ultimo_logout' . ")"), $nowTimestamp - $deltaTimestamp])
                        ->andWhere(['<', new \yii\db\Expression("UNIX_TIMESTAMP(" . UserProfile::tableName() . '.ultimo_accesso' . ")"), $nowTimestamp - $deltaTimestamp])
                        ->andWhere(['or',   // there is non the record in NotificationsSent or it is old enought
                                [NotificationsSent::tableName() . '.updated_at' => null],
                                ['<', NotificationsSent::tableName() . '.updated_at', $nowTimestamp - $deltaTimestamp]
                                ])
                        ;
            }
            
            $query->orderBy([UserProfile::tableName() . '.user_id' => SORT_ASC]);
            $query->select(UserProfile::tableName() . '.*');
            Console::stdout($query->createCommand()->rawSql . PHP_EOL);// die();
            
            $result = $query->all();
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        return $result;
    }


    /**
     * If the record $type+$user_id already exists, update modify_at date
     * 
     * @param int $type
     * @param int $user_id
     */
    protected function notifySentOnlyLast($type, $user_id)
    {
        try {
            $model = NotificationsSent::find()->andWhere(['user_id' => $user_id, 'type' => $type])->one();
            if (empty($model)) { // to be created
                $model = new NotificationsSent();
                $model->type = $type;
                $model->user_id = $user_id;
                $model->howmany = 1;
            } else {
                // non aggiorna updated_at $model->updateCounters(['howmany' => 1]);
                $model->howmany = $model->howmany + 1;
            }
            
            Console::stdout('notifySentOnlyLast: prima del save' . PHP_EOL);// die();
            $model->save(); // for update modify_at
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }
    
    protected function frequencyDeltaTimestamp($frequencyType) {
        
        $days = 0;
        if($frequencyType == NotificationsConfOpt::EMAIL_DAY) {
            $days = 1;
        } else if($frequencyType == NotificationsConfOpt::EMAIL_WEEK) {
            $days = 7;
        } else if($frequencyType == NotificationsConfOpt::EMAIL_MONTH) {
            $days = 30;
        } else {
            $days = PHP_INT_MAX;
        }

        if ($days) {
            return 86400 * $days;
        } else {
            return PHP_INT_MAX;
        }
        
    } // frequencyDeltaTimestamp
    
}