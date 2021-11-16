<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers
 * @category   CategoryName
 */

namespace open20\amos\admin\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserContact;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\socialauth\models\SocialIdmUser;
use Yii;
use yii\console\Application;
use yii\db\ActiveRecord;
use yii\web\Controller;

/**
 * @package open20\amos\admin\controllers
 */
class UserDropController extends Controller
{
    //Configuration array of models
    public $models = [];
    
    /**
     * @var AmosAdmin $adminModule
     */
    protected $adminModule;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->adminModule = AmosAdmin::instance();
        
        parent::init();

        //Pull configuration array
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'drop-config.php'));
    }

    /**
     * Delete the user without deleting the contents
     * @param $userID string
     */
    public function softDropEverything($userID){
        //Security Policy
        if(!\Yii::$app instanceof Application && !\Yii::$app->user->can('ADMIN') && $userID != Yii::$app->user->id) {
            throw new \Exception('Not allowed to drop other users');
        }

        //Find user to check if you can drop all
        $user = User::findOne($userID);

        if($user && $user->id) {
            //Delete private messages
            $this->dropMessages($user);

            $this->dropCommunity($user);

            //Drop friendships to avoid friendship zombies and similiar things
            $this->dropFriendshipsRelations($user);

            //Logout user to avoid any problem
            $this->logoutUser();

            //Mask the user data
            /** @var  $user User*/
            $user = $this->maskUserData($user);

            UserProfileUtility::deassignRoleFacilitator($user->userProfile);

            $user->userProfile->deactivateUserProfile();
            
            $this->dropSPIDLink($user->id);
        }
    }


    /**
     * This is a one way function to destroy all user datas on db keeping integrity
     * @param $userID integer
     * @return bool
     * @throws \Exception
     */
    public function dropEverything($userID) {
        //Security Policy
        if(!\Yii::$app->user->can('ADMIN') && $userID != Yii::$app->user->id) {
            throw new \Exception('Not allowed to drop other users');
        }

        //Find user to check if you can drop all
        $user = User::findOne($userID);

        if($user && $user->id) {
            //Delete all contents created by user
            $this->dropContents($user);

            //Delete private messages
            $this->dropMessages($user);

            //Drop friendships to avoid friendship zombies and similiar things
            $this->dropFriendshipsRelations($user);

            //Logout user to avoid any problem
            $this->logoutUser();

            //Mask the user data
            $user = $this->maskUserData($user);

            //And finally drop profile, this is the end of the user on the platform
            $this->dropUser($user);
    
            $this->dropSPIDLink($user->id);
        }

        return true;
    }

    /**
     * This action drop all contents created by user
     * @param $userRecord User
     * @return bool
     * @throws \Exception
     */
    public function dropContents($userRecord) {
        //Drop contents configured into the array
        foreach ($this->models as $modelClass=>$modelFields) {
            if(class_exists($modelClass)) {
                //Check if the table exists before delete
                if(\Yii::$app->db->schema->getTableSchema($modelClass::tableName(), true) === null) {
                    continue;
                }

                //Find all records to delete
                $records = $modelClass::findAll([
                    'created_by' => $userRecord->id
                ]);

                //Here all record fond will be dropped and fields cleaned
                $this->dropRecords($records, $modelFields);
            }
        }

        return true;
    }

    /**
     * Drop all gived record and clean datas of the fields array
     * @param $records ActiveRecord[]
     * @param $fields array
     * @return bool
     * @throws \Exception
     */
    public function dropRecords($records, $fields) {
        foreach ($records as $record) {
            foreach ($fields as $field) {
                $record->{$field} = '_deleted_';
            }

            //Store data
            $record->save(false);

            //Drop this content
            $record->delete();
        }

        return true;
    }

    /**
     * This action deletes all messages FROM and TO the current user
     * The conversation exists until at least one message exists
     * @param $userRecord
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function dropMessages($userRecord) {
        $q = \open20\amos\chat\models\Message::find();
        $q->where(['sender_id' => $userRecord->id]);
        $q->orWhere(['receiver_id' => $userRecord->id]);

        /**
         * All received and sended messages
         * @var Message[] $messages
         */
        $messages = $q->all();

        /**
         * Dropping all the messages the conversation never exists anymore
         */
        foreach ($messages as $message) {
            $message->delete();
        }

        return true;
    }

    /**
     * Here we drop all user relations to avoud zombies or unwanted datas
     * @param $userRecord User
     */
    public function dropFriendshipsRelations($userRecord) {
        $q = UserContact::find();
        $q->where(['user_id' => $userRecord->id]);
        $q->orWhere(['contact_id' => $userRecord->id]);

        /**
         * @var UserContact[] $contacts
         */
        $contacts = $q->all();

        /**
         * When this is dropped the user disaper from friends list in chat and other places
         */
        foreach ($contacts as $contact) {
            $contact->delete();
        }
    }

    /**
     * Here the user and the Profile is ready to be dropped
     * @param $userRecord User
     */
    public function dropUser($userRecord) {
        /**
         * @var UserProfile $profile
         */
        $profile = UserProfile::findOne(['user_id' => $userRecord->id]);

        if($profile && $profile->id) {
            UserProfileUtility::deassignRoleFacilitator($profile);
            $profile->delete();
        }

        //Goodbye my lover, Goodbye My Friend! You have been the one. You have been the one for me.
        $userRecord->delete();

        return true;
    }

    /**
     * Kick out user for security reasons
     * @return bool
     */
    public function logoutUser() {
        if(!\Yii::$app->user->can('ADMIN')) {
            $identity = \Yii::$app->user->getIdentity();

            if ($identity !== null) {
                \Yii::$app->user->switchIdentity(null);

                if (\Yii::$app->user->enableSession) {
                    \Yii::$app->getSession()->destroy();
                }
            }

            //Tis had to be true
            return \Yii::$app->user->getIsGuest();
        }
        return true;
    }


    /**
     * @param $user User
     * @return mixed
     */
    public function maskUserData($user){
        $blackList = ['id', 'nome', 'cognome', 'user_id', 'attivo', 'status', 'created_at', 'updated_at', 'deleted_at', 'created_by','updated_by', 'deleted_by', 'default_facilitatore'];
        $profile = $user->userProfile;
        $user->username = UserProfileUtility::DELETED_ACCOUNT_USERNAME_PREFIX . $user->id;
        $user->auth_key = '';
        $user->password_hash = '';
        $user->email = UserProfileUtility::makeDeletedUserEmail($user->id);

        $profile->nome = UserProfileUtility::DELETED_ACCOUNT_NAME;
        $profile->cognome = UserProfileUtility::DELETED_ACCOUNT_SURNAME;
        $profileAtributes = $profile->attributes;
        foreach ($profileAtributes as $attribute => $value){
            if(!in_array($attribute, $blackList)){
                $profile->$attribute = null;
            }
        }
        // delete profile image
        $image = $profile->getUserProfileImage();
        if(!empty($image)){
            $image->delete();
        }

        $profile->save(false);
        $user->save(false);

        return $user;
    }

    /**
     * @param $user
     */
    public function dropCommunity($user){
        $moduleCommunity  = \Yii::$app->getModule('community');
        $newCommManagerId = 1;
        $adminIds = \Yii::$app->authManager->getUserIdsByRole('ADMIN');
        if(!in_array($newCommManagerId, $adminIds)){
            $newCommManagerId = $adminIds[0];
        }
        if($moduleCommunity){
            $communityUsers = \open20\amos\community\models\CommunityUserMm::find()->andWhere(['user_id' => $user->id])->all();
            /** @var  $communityUser \open20\amos\community\models\CommunityUserMm */
            foreach ($communityUsers as $communityUser){
                if(!$this->hasCommunityManagers($communityUser->community_id, $user)){

                    if($this->isAdminCommunityParticipant($communityUser->community_id)){
                        $moduleCommunity->changeRoleCommunityUser($communityUser->community_id, $newCommManagerId, \open20\amos\community\models\CommunityUserMm::ROLE_COMMUNITY_MANAGER);
                    }
                    else {
                        $moduleCommunity->createCommunityUser($communityUser->community_id, \open20\amos\community\models\CommunityUserMm::STATUS_ACTIVE, \open20\amos\community\models\CommunityUserMm::ROLE_COMMUNITY_MANAGER, $newCommManagerId);
                    }
                    $this->sendMailYouHaveToChangeCM($communityUser->community_id, $user);

                }
                $moduleCommunity->deleteCommunityUser($communityUser->community_id, $user->id);
            }
        }
    }
    
    /**
     * @param int $userId
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function dropSPIDLink($userId)
    {
        /** @var \open20\amos\socialauth\Module $socialModule */
        $socialModule = Yii::$app->getModule('socialauth');
        if ($this->adminModule->enableDlSemplification) {
            if (!is_null($socialModule)) {
                /** @var SocialIdmUser $socialIdmUser */
                $socialIdmUser = $socialModule->findSocialIdmByUserId($userId);
                if (!is_null($socialIdmUser)) {
                    $socialIdmUser->delete();
                }
            }
        }
    }

    /**
     * @param $community_id
     * @return bool
     */
    public function hasCommunityManagers($community_id, $user){
        $countCm = \open20\amos\community\models\CommunityUserMm::find()
            ->andWhere(['community_id' =>$community_id])
            ->andWhere(['role' => \open20\amos\community\models\CommunityUserMm::ROLE_COMMUNITY_MANAGER])
            ->andWhere(['!=', 'user_id',$user->id])->count();
        return ($countCm > 0);
    }


    /**
     * @param $communityId
     * @return bool
     */
    public function isAdminCommunityParticipant($communityId){
        $userMm  =\open20\amos\community\models\CommunityUserMm::find()->andWhere(['community_id' => $communityId, 'user_id' => 1])->one();
        return !empty($userMm);
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $text
     * @param array $files
     * @param array $bcc
     */
    public function sendMailYouHaveToChangeCM($communityId, $user)
    {
        $subject = AmosAdmin::t('amosadmin',"E' necessario assegnare un nuovo community manager");
        $text = "<p>".AmosAdmin::t('amosadmin',"L'utente") . " {$user->userProfile->nomeCognome} con ID: {$user->id} " . AmosAdmin::t('amosadmin',"si è cancellato dalla piattaforma <br> Inserire un nuovo community manager alla community con id: ") . "$communityId</p>";
        /** @var \open20\amos\emailmanager\AmosEmail $mailModule */
        $mailModule = Yii::$app->getModule("email");
        if (isset($mailModule)) {
                if (isset(Yii::$app->params['email-assistenza'])) {
                    //use default platform email assistance
                    $from = Yii::$app->params['email-assistenza'];
                } else {
                    $from = 'assistenza@open20.it';
                }
            $tos = [Yii::$app->params['email-assistenza']];
            Email::sendMail($from, $tos, $subject, $text, [], [], [], 0, false);
        }
    }
}
