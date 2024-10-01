<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\controllers
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use open20\amos\core\user\User;
use open20\amos\organizzazioni\models\ProfiloSedi;
use open20\amos\organizzazioni\models\ProfiloSediUserMm;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\utility\EmailUtility;
use open20\amos\organizzazioni\utility\OrganizzazioniUtility;
use open20\amos\organizzazioni\widgets\JoinProfiloSediWidget;
use Yii;
use yii\base\Event;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class ProfiloSediController
 * This is the class for controller "ProfiloSediController".
 * @package open20\amos\organizzazioni\controllers
 */
class ProfiloSediController extends \open20\amos\organizzazioni\controllers\base\ProfiloSediController
{
    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setMmTableName($this->organizzazioniModule->createModel('ProfiloSediUserMm')->className());
        $this->setStartObjClassName($this->organizzazioniModule->createModel('ProfiloSedi')->className());
        $this->setMmStartKey('profilo_sedi_id');
        $this->setTargetObjClassName(AmosAdmin::instance()->createModel('UserProfile')->className());
        $this->setMmTargetKey('user_id');
        $this->setRedirectAction('update');
        $this->setModuleClassName(Module::className());
        $this->setCustomQuery(true);
        $this->on(M2MEventsEnum::EVENT_AFTER_DELETE_M2M, [$this, 'afterDeleteM2m']);
        $this->on(M2MEventsEnum::EVENT_BEFORE_CANCEL_ASSOCIATE_M2M, [$this, 'beforeCancelAssociateM2m']);
    }
    
    /**
     * @param Event $event
     */
    public function afterDeleteM2m($event)
    {
        $this->setRedirectArray([Url::previous()]);
    }
    
    /**
     * @param Event $event
     */
    public function beforeCancelAssociateM2m($event)
    {
        $urlPrevious = Url::previous();
        $id = \Yii::$app->request->get('id');
        
        if (strstr($urlPrevious, 'associate-headquarter-m2m')) {
            $this->setRedirectArray('/' . AmosAdmin::getModuleName() . '/user-profile/update?id=' . $id);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'user-network'
                        ],
                        'roles' => ['PROFILOSEDI_READ']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'elimina-m2m',
                            'annulla-m2m',
                            'associate-headquarter-m2m',
                            'join-headquarter'
                        ],
                        'roles' => ['ASSOCIATE_PROFILO_SEDI_TO_USER_PERMISSION']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'accept-user',
                            'reject-user',
                        ],
                        'roles' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'CONFIRM_ORGANIZZAZIONI_OR_SEDI_USER_REQUEST']
                    ],
                ]
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
     * disabled csrf token for send-message
     */
    
    public function beforeAction($action) {
      if ($action->id == 'user-network') {
        $this->enableCsrfValidation = false;
     // Yii::$app->controller->enableCsrfValidation = FALSE;
      }
      return true;
    }
    
    /**
     * @param int $userId
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getAssociateHeadquarterM2mQuery($userId)
    {
        /** @var ProfiloSedi $headquarter */
        $headquarter = $this->organizzazioniModule->createModel('ProfiloSedi');
        
        /** @var ActiveQuery $query */
        $query = $headquarter->getAssociateHeadquarterQuery($userId);
        
        $post = Yii::$app->request->post();
        if (isset($post['genericSearch'])) {
            $query->andFilterWhere(['like', $headquarter::tableName() . '.name', $post['genericSearch']]);
        }
        
        return $query;
    }
    
    /**
     * This method returns the columns showed in the associate headquarter m2m action,
     * which is the one the user can reach from his profile in the network tab.
     * @param int $userId
     * @return array
     */
    public function getAssociateHeadquarterM2mTargetColumns($userId)
    {
        /** @var ProfiloSedi $modelProfiloSedi */
        $modelProfiloSedi = $this->organizzazioniModule->createModel('ProfiloSedi');
        
        return [
            'profilo_sedi_type_id' => [
                'attribute' => 'profilo_sedi_type_id',
                'value' => 'profiloSediType.name'
            ],
            'name',
            [
                'attribute' => 'addressField',
                'format' => 'raw',
            ],
            [
                'label' => $modelProfiloSedi->getAttributeLabel('profilo'),
                'value' => 'profilo.name'
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{info}{view}{joinOrganization}',
                'buttons' => [
                    'joinOrganization' => function ($url, $model) {
                        $btn = JoinProfiloSediWidget::widget(['model' => $model, 'isGridView' => true]);
                        return $btn;
                    }
                ]
            ]
        ];
    }
    
    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAssociateHeadquarterM2m()
    {
        $userId = \Yii::$app->request->get('id');
        Url::remember();
        
        $this->setMmTableName($this->organizzazioniModule->createModel('ProfiloSediUserMm')->className());
        $this->setStartObjClassName(User::className());
        $this->setMmStartKey('user_id');
        $this->setTargetObjClassName($this->organizzazioniModule->createModel('ProfiloSedi')->className());
        $this->setMmTargetKey('profilo_sedi_id');
        $this->setRedirectAction('update');
        $this->setTargetUrl('associate-headquarter-m2m');
        $this->setCustomQuery(true);
        $userProfileId = User::findOne($userId)->getProfile()->id;
        $this->setRedirectArray('/' . AmosAdmin::getModuleName() . '/user-profile/update?id=' . $userProfileId . '#tab-network');
        return $this->actionAssociaM2m($userId);
        
    }
    
    /**
     * @param $headquarterId
     * @param bool $accept
     * @param null $redirectAction
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionJoinHeadquarter($headquarterId, $accept = false, $redirectAction = null)
    {
        $defaultAction = 'index';
        
        if (empty($redirectAction)) {
            $urlPrevious = Url::previous();
            $redirectAction = $urlPrevious;
        }
        if (!$headquarterId) {
            \Yii::$app->getSession()->addFlash('danger', Module::tHtml('amosorganizzazioni', "It is not possible to subscribe the user. Missing parameter headquarter."));
            return $this->redirect($defaultAction);
        }
        
        $nomeCognome = ' ';
        $organizationName = '';
        $headquarterName = '';
        $userId = Yii::$app->request->get('userId');
        if (isset($userId) && ($userId > 0)) {
            $user = User::findOne($userId);
        } else {
            /** @var User $user */
            $user = Yii::$app->user->identity;
            $userId = $user->id;
        }
        $userProfile = $user->userProfile;
        if (!is_null($userProfile)) {
            $nomeCognome = " '" . $userProfile->nomeCognome . "' ";
        }
        
        /** @var ProfiloSedi $profiloSediModel */
        $profiloSediModel = $this->organizzazioniModule->createModel('ProfiloSedi');
        $headquarter = $profiloSediModel::findOne($headquarterId);
        if (!is_null($headquarter)) {
            $organization = $headquarter->profilo;
            $headquarterName = " '" . $headquarter->name . "'";
            $organizationName = "'" . $organization->name . "'";
            if ($this->organizzazioniModule->enableWorkflow  && ($organization->status != $organization->getValidatedStatus())) {
                Yii::$app->getSession()->addFlash('danger', Module::tHtml('amosorganizzazioni', '#join_headquarter_not_validated_organization', [
                    'headquarterName' => $headquarterName,
                    'organizationName' => $organizationName
                ]));
                $action = (isset($redirectAction) ? $redirectAction : $defaultAction);
                return $this->redirect($action);
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::tHtml('amosorganizzazioni', '#join_headquarter_not_found_headquarter'));
            $action = (isset($redirectAction) ? $redirectAction : $defaultAction);
            return $this->redirect($action);
        }
        
        /** @var ProfiloSediUserMm $profiloSediUserMm */
        $profiloSediUserMm = $this->organizzazioniModule->createModel('ProfiloSediUserMm');
        $userHeadquarter = $profiloSediUserMm::findOne(['profilo_sedi_id' => $headquarterId, 'user_id' => $userId]);
        
        // Verify if the user is already in the headquarter user relation table
        if (is_null($userHeadquarter)) {
            $organizationRefereesIds = OrganizzazioniUtility::getOrganizationReferees($headquarter->profilo_id, true);
            if (in_array($userId, $organizationRefereesIds)) {
                // The user is a legal representative or a operative referee for the organization, then cannot be a member now.
                // In future modify this code if you want to enable the roles in MM table like communities (and remove this comment).
                Yii::$app->getSession()->addFlash('danger', Module::tHtml('amosorganizzazioni', '#join_headquarter_already_referee', [
                    'nomeCognome' => $nomeCognome,
                    'organizationName' => $organizationName,
                    'headquarterName' => $headquarterName
                ]));
                $action = (isset($redirectAction) ? $redirectAction : $defaultAction);
                return $this->redirect($action);
            } else {
                // Iscrivo l'utente alla sede
                /** @var ProfiloSediUserMm $userHeadquarter */
                $userHeadquarter = $this->organizzazioniModule->createModel('ProfiloSediUserMm');
                $userHeadquarter->profilo_sedi_id = $headquarterId;
                $userHeadquarter->user_id = $userId;
                if (!$this->organizzazioniModule->enableConfirmUsersJoinRequests) {
                    // If the confirm of an user that request to join an organization headquarter is disabled set directly the active status and do anything else.
                    $userHeadquarter->status = ProfiloSediUserMm::STATUS_ACTIVE;
                    $message = Module::tHtml('amosorganizzazioni', "You are now linked to the headquarter ") . ' ' . $headquarterName;
                } else {
                    // If the confirm of an user that request to join an organization headquarter is enabled set the request confirm status and send an email to the legal representative.
                    $userHeadquarter->status = ProfiloSediUserMm::STATUS_WAITING_REQUEST_CONFIRM;
                    $message = Module::tHtml('amosorganizzazioni', '#join_headquarter_request_forwarded_to_referees', [
                        'organizationName' => $organizationName,
                        'headquarterName' => $headquarterName
                    ]);
                    $emailUtil = new EmailUtility(
                        EmailUtility::REGISTRATION_REQUEST,
                        $userHeadquarter->role,
                        $headquarter,
                        $userProfile->nomeCognome,
                        '',
                        null,
                        $userProfile->user_id
                    );
                    $organizationRefereesEmails = $emailUtil->getOrganizationRefereesMailList($userHeadquarter->profiloSedi->profilo_id);
                    $subject = $emailUtil->getSubject();
                    $text = $emailUtil->getText();
                    foreach ($organizationRefereesEmails as $to) {
                        $emailUtil->sendMail(null, $to, $subject, $text, [], []);
                    }
                }
                $ok = $userHeadquarter->save(false);
            }
            if ($ok) {
                Yii::$app->getSession()->addFlash('success', $message);
                if (strpos($redirectAction, 'associate-headquarter-m2m') && !Yii::$app->user->can('ASSOCIATE_ORGANIZZAZIONI_TO_USER', ['model' => $userProfile])) {
                    $redirectAction = '/' . AmosAdmin::getModuleName() . '/user-profile/update?id=' . $userProfile->id . '#tab-network';
                }
                $action = (isset($redirectAction) ? $redirectAction : $defaultAction);
                return $this->redirect($action);
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::tHtml('amosorganizzazioni', '#join_headquarter_error', [
                    'nomeCognome' => $nomeCognome,
                    'headquarterName' => $headquarterName
                ]));
                return $this->redirect($defaultAction);
            }
        } else {
            if ($userHeadquarter->status == ProfiloSediUserMm::STATUS_WAITING_REQUEST_CONFIRM) {
                $messagePlaceholder = '#join_headquarter_user_waiting_request_confirm';
            } elseif ($userHeadquarter->status == ProfiloSediUserMm::STATUS_ACTIVE) {
                $messagePlaceholder = '#join_headquarter_user_already_joined';
            } elseif ($userHeadquarter->status == ProfiloSediUserMm::STATUS_REJECTED) {
                $messagePlaceholder = '#join_headquarter_user_rejected';
            } else {
                $messagePlaceholder = '#join_headquarter_user_already_joined';
            }
            $this->addFlash('info', Module::tHtml('amosorganizzazioni', $messagePlaceholder, [
                'nomeCognome' => $nomeCognome,
                'organizationName' => $organizationName,
                'headquarterName' => $headquarterName
            ]));
            return $this->redirect($defaultAction);
        }
    }
    
    /**
     * Organization referees accepts the user membership request to an organization headquarter
     *
     * @param int $profiloSediId
     * @param $userId
     * @return \yii\web\Response
     */
    public function actionAcceptUser($profiloSediId, $userId)
    {
        return $this->redirect($this->acceptOrRejectUser($profiloSediId, $userId, true));
    }
    
    /**
     * Organization referees rejects the user membership request to an organization headquarter
     *
     * @param int $profiloSediId
     * @param int $userId
     * @return \yii\web\Response
     */
    public function actionRejectUser($profiloSediId, $userId)
    {
        return $this->redirect($this->acceptOrRejectUser($profiloSediId, $userId, false));
    }
    
    /**
     * @param int $profiloSediId
     * @param int $userId
     * @param bool $acccept - true if User membership request has been accepted by organization referees, false if rejected
     * @return string
     */
    private function acceptOrRejectUser($profiloSediId, $userId, $acccept)
    {
        /** @var ProfiloSediUserMm $profiloSediUserMm */
        $profiloSediUserMm = $this->organizzazioniModule->createModel('ProfiloSediUserMm');
        /** @var ProfiloSediUserMm $userHeadquarter */
        $userHeadquarter = $profiloSediUserMm::findOne(['profilo_sedi_id' => $profiloSediId, 'user_id' => $userId]);
        $redirectUrl = '';
        
        if (!is_null($userHeadquarter)) {
            $nomeCognome = " ";
            $headquarterName = '';
            $redirectUrl = Url::previous();
            
            $user = User::findOne($userId);
            $userProfile = $user->userProfile;
            if (!is_null($userProfile)) {
                $nomeCognome = "'" . $userProfile->nomeCognome . "'";
            }
            
            /** @var ProfiloSedi $profiloSediModel */
            $profiloSediModel = $this->organizzazioniModule->createModel('ProfiloSedi');
            $headquarter = $profiloSediModel::findOne($profiloSediId);
            if (!is_null($headquarter)) {
                $headquarterName = "'" . $headquarter->name . "'";
            }
            
            if ($acccept) {
                $retVal = $this->welcomeUserOperations($headquarter, $userHeadquarter);
            } else {
                $retVal = $this->rejectUserOperations($headquarter, $userHeadquarter);
            }
            
            $emailUtil = new EmailUtility(
                $retVal['emailType'],
                $userHeadquarter->role,
                $headquarter,
                $userProfile->nomeCognome,
                $retVal['refereeName'],
                null,
                $userProfile->user_id
            );
            $subject = $emailUtil->getSubject();
            $text = $emailUtil->getText();
            $emailUtil->sendMail(null, $user->email, $subject, $text, [], []);
            
            $message = Module::tHtml('amosorganizzazioni', $retVal['messagePlaceholder'], [
                'nomeCognome' => $nomeCognome,
                'headquarterName' => $headquarterName
            ]);
            $this->addFlash('success', $message);
        }
        return $redirectUrl;
    }
    
    /**
     * Operations when the user accept the invitation.
     * @param ProfiloSedi $headquarter
     * @param ProfiloSediUserMm $userHeadquarter
     * @return array
     */
    protected function welcomeUserOperations($headquarter, $userHeadquarter)
    {
        $userHeadquarter->status = $userHeadquarter::STATUS_ACTIVE;
        $userHeadquarter->save(false);
        return [
            'emailType' => EmailUtility::WELCOME,
            'messagePlaceholder' => '#join_headquarter_user_accepted',
            'refereeName' => ''
        ];
    }
    
    /**
     * Operations when the user reject the invitation.
     * @param ProfiloSedi $headquarter
     * @param ProfiloSediUserMm $userHeadquarter
     * @return array
     */
    protected function rejectUserOperations($headquarter, $userHeadquarter)
    {
        $userHeadquarter->status = $userHeadquarter::STATUS_REJECTED;
        $userHeadquarter->save(false);
        $userHeadquarter->delete();
        return [
            'emailType' => EmailUtility::REGISTRATION_REJECTED,
            'messagePlaceholder' => '#join_headquarter_user_rejected_successfully',
            'refereeName' => Yii::$app->user->identity->userProfile->getNomeCognome()
        ];
    }
    
    /**
     * @param int $userId
     * @param bool $isUpdate
     * @return string
     */
    public function actionUserNetwork($userId, $isUpdate = false)
    {
        if (\Yii::$app->request->isAjax) {
            $this->setUpLayout(false);
            
            return $this->render('user-network', [
                'userId' => $userId,
                'isUpdate' => $isUpdate
            ]);
        }
        return '';
    }
}
