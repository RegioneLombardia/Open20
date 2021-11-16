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
use open20\amos\core\controllers\CrudController;
use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\notificationmanager\AmosNotify;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class UserContactController
 * @package open20\amos\admin\controllers
 */
class UserContactController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'connect',
                            'associate-contacts',
                            'associa-m2m',
                            'annulla-m2m',
                            'delete-contact',
                            'send-reminder'
                        ],
                        'roles' => ['@']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        
        
        $this->setModelObj(AmosAdmin::instance()->createModel('UserContact'));
        $this->setModelSearch(AmosAdmin::instance()->createModel('UserContactSearch'));
        
        $gridView = [
            'name' => 'grid',
            'label' => AmosAdmin::t('amosadmin', '{iconaTabella}' . Html::tag('p', AmosAdmin::t('amosadmin', 'Tabella')), [
                'iconaTabella' => AmosIcons::show('view-list-alt')
            ]),
            'url' => '?currentView=grid'
        ];
        
        $iconView = [
            'name' => 'icon',
            'label' => AmosAdmin::t('amosadmin', '{iconaElenco}' . Html::tag('p', AmosAdmin::t('amosadmin', 'Icone')), [
                'iconaElenco' => AmosIcons::show('grid')
            ]),
            'url' => '?currentView=icon'
        ];
        
        $this->setAvailableViews([
            'grid' => $gridView,
            'icon' => $iconView,
        ]);
        
        parent::init();
        $this->setUpLayout();
        
        $this->setMmTableName(UserContact::className());
        $this->setStartObjClassName(AmosAdmin::instance()->model('UserProfile'));
        $this->setMmStartKey('user_id');
        $this->setTargetObjClassName(AmosAdmin::instance()->model('UserProfile'));
        $this->setMmTargetKey('id');
        $this->setRedirectAction('update');
        $this->setOptions(['#' => 'tab-network']);
        $this->setTargetUrl('associate-contacts');
        $this->setCustomQuery(true);
        $this->setModuleClassName(AmosAdmin::className());
        
        $this->on(M2MEventsEnum::EVENT_BEFORE_CANCEL_ASSOCIATE_M2M, [$this, 'beforeCancelAssociateM2m']);
    }
    
    /**
     * @param $contactId
     * @param null $userId
     * @param bool $accept
     * @return \yii\web\Response
     */
    public function actionConnect($contactId, $userId = null, $accept = false)
    {
        if (!empty($contactId)) {
            if (empty($userId)) {
                //Logged user sends connection request to contactId
                $userId = Yii::$app->user->id;
                //check userId and contactId are different
                if ($userId != $contactId) {
                    //check if a connection request is already present
                    $userContact = UserContact::findOne(['user_id' => $userId, 'contact_id' => $contactId]);
                    
                    /**
                     * @var $contactProfile UserProfile
                     */
                    $contactProfile = UserProfile::findOne(['user_id' => $contactId, 'validato_almeno_una_volta' => true]);
                    
                    if (empty($userContact) && !empty($contactProfile)) {
                        $userContact = UserContact::findOne(['user_id' => $contactId, 'contact_id' => $userId]);
                        if (empty($userContact)) {
                            //if there is no connection between $userId and $contactId create a new userContact
                            $userContact = new UserContact();
                            $userContact->user_id = $userId;
                            $userContact->contact_id = $contactId;
                            $userContact->status = UserContact::STATUS_INVITED;
                            $userContact->save();
                            $this->sendContactRequest($userContact);
                        }
                    } elseif (empty($contactProfile)) {
                        Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'User Not Validated, Unable to Connect'));
                    }
                }
            } else {
                $userContact = UserContact::findOne(['user_id' => $userId, 'contact_id' => $contactId]);
                // the user contactId decides to accept or refuse the connection request sent by userId
                // check if connection request to accept exists and if the logged user correspond to the user who is accepting/refusing request
                if (!empty($userContact) && $contactId == Yii::$app->user->id) {
                    if ($accept) {
                        $userContact->status = UserContact::STATUS_ACCEPTED;
                        $userContact->accepted_at = date('Y-m-d H:i:s');
                        $userContact->save();
                        $this->sendContactRequest($userContact, true);
                    } else {
                        $userContact->status = UserContact::STATUS_REFUSED;
                        $userContact->delete();
                    }
                }
            }
        }
        
        return $this->redirect(Url::previous());
    }
    
    /**
     * @return mixed
     */
    public function actionAssociateContacts($id)
    {
        Url::remember();
        return $this->actionAssociaM2m($id);
    }
    
    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDeleteContact($id)
    {
        $userId = Yii::$app->user->id;
        
        $userContact = UserContact::findOne($id);
        
        if ($userContact && $userContact->user_id != $userId && $userContact->contact_id != $userId) {
            Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'Unable to delete Connection'));
            
            return $this->redirect(Url::previous());
        }
        
        $userContact->delete();
        
        return $this->redirect(Url::previous());
    }
    
    /**
     * @param $event
     */
    public function beforeCancelAssociateM2m($event)
    {
        $this->setRedirectArray(['/'.AmosAdmin::getModuleName().'/user-profile/update', 'id' => \Yii::$app->request->get('id')]);
    }
    
    /**
     * @param UserContact $model
     * @param bool|false $isAcceptance
     */
    public function sendContactRequest($model, $isAcceptance = false)
    {
        $user = User::findOne($model->user_id);
        $invitedUser = User::findOne($model->contact_id);
        if (!is_null($invitedUser) && !is_null($user)) {
            if (!$isAcceptance) {
                $tos = [$invitedUser->email];
                $contactProfile = $user->getProfile();
                $message = AmosAdmin::t('amosadmin', "would like to connect with you and add you to the contact list");
                $messageLink = AmosAdmin::t('amosadmin', 'to accept or refuse the invitation');
                $moduleMyActivities = Yii::$app->getModule('myactivities');
                if (isset($moduleMyActivities)){
                    $url = Yii::$app->urlManager->createAbsoluteUrl('myactivities/my-activities/index');
                }
            } else {
                /** @var AmosNotify $notifyModule */
                $notifyModule = Yii::$app->getModule('notify');
                if ($notifyModule && $notifyModule->hasMethod('contactAccepted')) {
                    $notifyModule->contactAccepted($user, $invitedUser);
                    return;                    
                } else {
                    $tos = [$user->email];
                    $contactProfile = $invitedUser->getProfile();
                    $message = AmosAdmin::t('amosadmin',"accepted your connection invitation and is now active in your contact list");
                    $messageLink = AmosAdmin::t('amosadmin', "and open the network section in your profile to check the status of your contacts");
                }
            }
            if (!isset($url)){
                $url = Yii::$app->urlManager->createAbsoluteUrl('dashboard');
            }
            $subject = $contactProfile->getNomeCognome() . " " . $message;
            $text = $this->renderMailPartial('email', [
                'contactProfile' => $contactProfile,
                'message' => $message,
                'url' => $url,
                'messageLink' => $messageLink
            ]);
            $this->sendMail(null, $tos, $subject, $text);
        }
    }
    
    /**
     * @param int $id - the UserContact model id
     * @return \yii\web\Response
     */
    public function actionSendReminder($id)
    {
        /**
         * @var $model UserContact
         */
        $model = $this->findModel($id);
        $userId = Yii::$app->user->id;
        
        /**
         * If the contact row not exists
         * Or Contact Row Not Sent By You
         * Or Status is not "Invited"
         */
        if (!$model || $model->created_by != $userId || $model->status != UserContact::STATUS_INVITED) {
            Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'Can\'t Send Reminder'));
            
            return $this->redirect(Url::previous());
        }
        
        $reminderCount = empty($model->reminders_count) ? 1 : ($model->reminders_count + 1);
        $model->reminders_count = $reminderCount;
        $model->last_reminder_at = date('Y-m-d H:i:s');
        $model->save();
        $this->sendContactRequest($model);
        return $this->redirect(Url::previous());
    }
    
    /**
     * @param string $from
     * @param array $tos
     * @param string $subject
     * @param string $text
     * @param array $files
     * @param array $bcc
     */
    public function sendMail($from, $tos, $subject, $text, $files = [], $bcc = [])
    {
        /** @var \open20\amos\emailmanager\AmosEmail $mailModule */
        $mailModule = Yii::$app->getModule("email");
        if (isset($mailModule)) {
            if (is_null($from)) {
                if (isset(Yii::$app->params['email-assistenza'])) {
                    //use default platform email assistance
                    $from = Yii::$app->params['email-assistenza'];
                } else {
                    $from = 'assistenza@open20.it';
                }
            }
            Email::sendMail($from, $tos, $subject, $text, $files, $bcc, [], 0, false);
        }
    }

}
