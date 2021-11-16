<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\controllers\base
 * @category   CategoryName
 */

namespace open20\amos\invitations\controllers\base;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\Email;
use open20\amos\invitations\models\Invitation;
use open20\amos\invitations\models\InvitationUser;
use open20\amos\invitations\Module;
use open20\amos\invitations\utility\InvitationsUtility;
use Yii;
use yii\helpers\Url;
use yii\validators\EmailValidator;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class InvitationController
 * InvitationController implements the CRUD actions for Invitation model.
 *
 * @property \open20\amos\invitations\models\Invitation $model
 * @property \open20\amos\invitations\models\search\InvitationSearch $modelSearch
 *
 * @package open20\amos\invitations\controllers\base
 */
class InvitationController extends CrudController
{
    public $moduleName = null;
    public $contextModelId = null;
    public $returnTo = null;
    
    /**
     * @var Module|null $invitationsModule
     */
    public $invitationsModule = null;
    
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->invitationsModule = Module::instance();
        
        $this->moduleName = Yii::$app->request->get('moduleName');
        $this->contextModelId = Yii::$app->request->get('contextModelId');
        $this->returnTo = Yii::$app->request->get('returnTo');
        
        $this->setModelObj($this->invitationsModule->createModel('Invitation'));
        $this->setModelSearch($this->invitationsModule->createModel('InvitationSearch'));
        
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', Module::t('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);
        
        parent::init();
    }
    
    /**
     * Lists all Invitation models.
     * @param string|null $layout
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex($layout = null)
    {
        Url::remember();
        
        $ret = $this->importInvitationsAction();
        $this->handleImportResult($ret);
        $this->sendSelectedInvitationsAction();
        
        /* add params */
        $this->setCreateNewBtnLabel();
        $this->setDataProvider(
            $this->getModelSearch()->search(Yii::$app->request->getQueryParams())
        );
        
        return parent::actionIndex();
    }
    
    /**
     * @param array $ret
     */
    private function handleImportResult($ret)
    {
        if (!is_null($ret)) {
            if ($ret['error'] != '') {
                Yii::$app->getSession()->addFlash('danger', Module::t('amosinvitations', 'Import error: {message}', ['message' => $ret['error']]));
            } else {
                if ($ret['num_imp'] > 0) {
                    Yii::$app->getSession()->addFlash('success', Module::t('amosinvitations', 'Properly imported {num} invitations!', ['num' => $ret['num_imp']]));
                }
                if ($ret['num_no_imp'] > 0) {
                    $message = Module::t('amosinvitations', 'Not imported {num} invitations!', ['num' => $ret['num_no_imp']]);
                    if (strlen($ret['imp_mail_error']) > 0) {
                        $message .= '<br>' . $ret['imp_mail_error'];
                    }
                    Yii::$app->getSession()->addFlash('warning', $message);
                }
            }
        }
    }
    
    /**
     * @return array|null
     * @throws \yii\db\Exception
     */
    private function importInvitationsAction()
    {
        $ret = [
            'error' => '',
            'num_imp' => 0,
            'num_no_imp' => 0,
            'num_not_valid_mails' => 0,
            'num_users_already_present' => 0,
            'imp_mail_error' => '',
        ];
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            
            $invitation = Yii::$app->request->post('InvitationSearch');
            if (!is_null($invitation)) {
                $moduleName = $invitation['moduleName'];
                $contextModelId = $invitation['contextModelId'];
            } else {
                $moduleName = null;
                $contextModelId = null;
            }
            
            $submitImport = Yii::$app->request->post('submit-import');
            if (!empty($submitImport)) {
                if ((isset($_FILES['import-file']['tmp_name']) && (!empty($_FILES['import-file']['tmp_name'])))) {
                    $inputFileName = $_FILES['import-file']['tmp_name'];
                    $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                    
                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();
                    $validator = new EmailValidator();
                    $notValidEmails = [];
                    $usersAlreadyPresentEmails = [];
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                            NULL,
                            TRUE,
                            FALSE);
                        $invitationArray = $rowData[0];
                        $email = $invitationArray[0];
                        $name = $invitationArray[1];
                        $surname = $invitationArray[2];
                        $message = $invitationArray[3];
                        if ($validator->validate($email)) {
                            if (InvitationsUtility::checkUserAlreadyPresent($email, true)) {
                                $usersAlreadyPresentEmails[] = "'$email'";
                                $ret['num_no_imp']++;
                                $ret['num_users_already_present']++;
                            } else {
                                /** @var InvitationUser $invitationUserModel */
                                $invitationUserModel = $this->invitationsModule->createModel('InvitationUser');
                                
                                //find invitation user
                                $invitationUser = $invitationUserModel::getInvitationUserFromEmail($email);
                                if (empty($invitationUser)) {
                                    /** @var InvitationUser $invitationUser */
                                    $invitationUser = $this->invitationsModule->createModel('InvitationUser');
                                    $invitationUser->email = $email;
                                    $invitationUser->save(false);
                                }
                                /** @var Invitation $invitation */
                                $invitation = $this->invitationsModule->createModel('Invitation');
                                $invitation->invitation_user_id = $invitationUser->id;
                                $invitation->name = $name;
                                $invitation->surname = $surname;
                                $invitation->message = $message;
                                
                                if ($moduleName && $contextModelId) {
                                    $invitation->module_name = $this->moduleName;
                                    $invitation->context_model_id = $this->contextModelId;
                                }
                                
                                $invitation->save(false);
                                $ret['num_imp']++;
                            }
                        } else {
                            $notValidEmails[] = "'$email'";
                            $ret['num_not_valid_mails']++;
                            $ret['num_no_imp']++;
                        }
                    }
                    if ($ret['num_not_valid_mails'] > 0) {
                        $ret['imp_mail_error'] .= Module::t('amosinvitations', '#import_invitations_not_valid_mails') . ': ' . implode(', ', $notValidEmails);
                    }
                    if ($ret['num_users_already_present'] > 0) {
                        if (strlen($ret['imp_mail_error']) > 0) {
                            $ret['imp_mail_error'] .= '<br>';
                        }
                        $ret['imp_mail_error'] .= Module::t('amosinvitations', '#import_invitations_users_already_present') . ': ' . implode(', ', $usersAlreadyPresentEmails);
                    }
                }
            } else {
                $ret = null;
            }
            
            $transaction->commit();
        } catch (\Exception $e) {
            $ret['error'] = $e->getMessage();
            $transaction->rollBack();
            
            return $ret;
        }
        
        return $ret;
    }
    
    /**
     *
     */
    private function sendSelectedInvitationsAction()
    {
        $i = 0;
        $submitInvitation = Yii::$app->request->post('submit-invitation');
        if (!empty($submitInvitation)) {
            $selection = Yii::$app->request->post('Invitation');
            if (!empty($selection) && isset($selection['selection'])) {
                foreach ($selection['selection'] as $id) {
                    /** @var Invitation $invitationModel */
                    $invitationModel = $this->invitationsModule->createModel('Invitation');
                    /** @var Invitation $invitation */
                    $invitation = $invitationModel::findOne($id);
                    if (!empty($invitation)) {
                        if (!InvitationsUtility::checkUserAlreadyPresent($invitation->invitationUser->email)) {
                            if ($invitation->send) {
                                $this->resend($invitation, false);
                            } else {
                                $this->sendMailInvitation($invitation);
                            }
                            $i++;
                        }
                    }
                }
            } else {
                Yii::$app->getSession()->addFlash('warning', Module::t('amosinvitations', 'No invitation sent'));
            }
        }
        
        if ($i == 1) {
            Yii::$app->getSession()->addFlash('success', Module::t('amosinvitations', 'An invitation has been sent'));
        } elseif ($i > 1) {
            Yii::$app->getSession()->addFlash('success', Module::t('amosinvitations', '{num} invitations were sent', ['num' => $i]));
        }
    }
    
    /**
     * @param Invitation $invitation
     * @return Invitation
     */
    protected function sendMailInvitation($invitation)
    {
        if (!empty($invitation)) {
            $text = '';
            $subjectText = '';
            if ($this->moduleName && $this->contextModelId) {
                $modulename = $this->moduleName;
                $module = \Yii::$app->getModule($modulename);
                if (method_exists($module, 'renderInvitationEmailText')) {
                    $text = $module->renderInvitationEmailText($invitation);
                }
                if (method_exists($module, 'renderInvitationEmailSubject')) {
                    $subjectText = $module->renderInvitationEmailSubject($invitation);
                }
            }
            
            if (isset(Yii::$app->params['email-assistenza'])) {
                //use default platform email assistance
                $from = Yii::$app->params['email-assistenza'];
            } else {
                $assistance = isset(Yii::$app->params['assistance']) ? Yii::$app->params['assistance'] : [];
                $from = isset($assistance['email']) ? $assistance['email'] : '';
            }
            $tos = [$invitation->invitationUser->email];
            
            $moduleinvitation = \Yii::$app->getModule('invitations');
            if (isset($moduleinvitation)) {
                $subject = Module::t($moduleinvitation->subjectCategory, $moduleinvitation->subjectPlaceholder, ['platformName' => Yii::$app->name]);
            } else {
                $subject = Module::t('amosinvitations', '#subject-invite', ['platformName' => Yii::$app->name]);
            }
            
            if (empty($text)) {
                $text = $this->renderPartial('_invitation_email', ['invitation' => $invitation]);
            }
            $invitation->send = (int)Email::sendMail($from, $tos, $subject, $text, [], [], [], 0, false);
            $invitation->send_time = date('Y-m-d H:i:s');
            $invitation->save(false);
        }
        
        return $invitation;
    }
    
    /**
     * Lists all Invitation models.
     * @param string|null $layout
     * @return mixed
     */
    public function actionIndexAll($layout = null)
    {
        Url::remember();
        
        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        
        $ret = $this->importInvitationsAction();
        $this->handleImportResult($ret);
        $this->sendSelectedInvitationsAction();
        
        /* add params */
        $this->setCreateNewBtnLabel();
        
        $this->setUpLayout('list');
        if ($layout) {
            $this->setUpLayout($layout);
        }
        
        return $this->render(
            'index_all',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null,
                'moduleName' => ($this->moduleName) ? $this->moduleName : null,
                'contextModelId' => ($this->contextModelId) ? $this->contextModelId : null,
            ]
        );
    }
    
    /**
     * Displays a single Invitation model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }
    
    /**
     * Creates a new Invitation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        
        /** @var Invitation $invitation */
        $invitation = $this->invitationsModule->createModel('Invitation');
        
        /** @var InvitationUser $invitationUser */
        $invitationUser = $this->invitationsModule->createModel('InvitationUser');

//        $moduleName = Yii::$app->request->get('moduleName');
//        $contextModelId = Yii::$app->request->get('contextModelId');
        if ($this->moduleName && $this->contextModelId) {
            $invitation->module_name = $this->moduleName;
            $invitation->context_model_id = $this->contextModelId;
        }
        
        if (!Yii::$app->request->isAjax) {
            if ($invitation->load(Yii::$app->request->post()) && $invitationUser->load(Yii::$app->request->post())) {
                if (InvitationsUtility::checkUserAlreadyPresent($invitationUser->email)) {
                    return $this->render(
                        'create',
                        [
                            'invitation' => $invitation,
                            'invitationUser' => $invitationUser,
                        ]
                    );
                }
            }
        }
        
        return $this->sendInvitation('create', $invitation, $invitationUser);
    }
    
    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionReSend($id)
    {
        $this->setUpLayout('form');
        
        /** @var Invitation $invitationModel */
        $invitationModel = $this->invitationsModule->createModel('Invitation');
        
        /** @var Invitation $invitation */
        $invitation = $invitationModel::findOne($id);
        $email = $invitation->invitationUser->email;
        
        if ($this->moduleName && $this->contextModelId) {
            $invitation->module_name = $this->moduleName;
            $invitation->context_model_id = $this->contextModelId;
        }
        
        if (InvitationsUtility::checkUserAlreadyPresent($email)) {
            if (Yii::$app->user->can('ADMIN')) {
                return $this->redirect([
                    'index-all',
                    'moduleName' => ($this->moduleName ? $this->moduleName : null),
                    'contextModelId' => ($this->contextModelId ? $this->contextModelId : null)
                ]);
            } else {
                return $this->redirect([
                    'index',
                    'moduleName' => ($this->moduleName ? $this->moduleName : null),
                    'contextModelId' => ($this->contextModelId ? $this->contextModelId : null)
                ]);
            }
        }
        
        $invitationSent = $this->resend($invitation);
        if (Yii::$app->user->can('INVITATIONS_ADMINISTRATOR')) {
            return $this->redirect([
                'index-all',
                'moduleName' => ($this->moduleName ? $this->moduleName : null),
                'contextModelId' => ($this->contextModelId ? $this->contextModelId : null)
            ]);
        } else {
            return $this->redirect([
                'index',
                'moduleName' => ($this->moduleName ? $this->moduleName : null),
                'contextModelId' => ($this->contextModelId ? $this->contextModelId : null)
            ]);
        }
    }
    
    
    /**
     * @param Invitation $invitation
     * @param bool $showAddflash
     */
    public function resend($invitation, $showAddflash = true)
    {
        /** @var Invitation $newInvitation */
        $newInvitation = $this->invitationsModule->createModel('Invitation');
        $newInvitation->invitation_user_id = $invitation->invitation_user_id;
        $newInvitation->message = $invitation->message;
        $newInvitation->name = $invitation->name;
        $newInvitation->surname = $invitation->surname;
        $newInvitation->module_name = $invitation->module_name;
        $newInvitation->context_model_id = $invitation->context_model_id;
        $newInvitation->save();
        $invitationSent = $this->sendMailInvitation($newInvitation);
        if ($invitationSent->save()) {
            if ($showAddflash) {
                Yii::$app->getSession()->addFlash('success', Module::t('amosinvitations', 'Item sended'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::t('amosinvitations', 'Item not sended, check data'));
        }
    }
    
    /**
     * @param string $actionView
     * @param Invitation $invitation
     * @param InvitationUser $invitationUser
     * @return string|\yii\web\Response
     */
    protected function sendInvitation($actionView, $invitation, $invitationUser)
    {
        $message = '';
        if ($invitationUser->load(Yii::$app->request->post())) {
            $retCheckUser = InvitationsUtility::checkUserAlreadyPresent($invitationUser->email, true, true);
            if ($retCheckUser['present']) {
                if (!is_null($actionView)) {
                    \Yii::$app->getSession()->addFlash('danger', $retCheckUser['message']);
                    return $this->render($actionView, [
                        'invitation' => $invitation,
                        'invitationUser' => $invitationUser,
                    ]);
                }
                
                $sessionUrl = Yii::$app->session->get(Module::beginCreateNewSessionKey());
                if (!is_null($sessionUrl)) {
                    return $this->redirect($sessionUrl);
                }
                
                if (!empty($this->returnTo)) {
                    $explode = explode('_', $this->returnTo);
                    if (count($explode) == 2) {
                        $returnType = $explode[0] . '_';
                        $modelId = $explode[1];
                        if ($returnType == InvitationsUtility::RETURN_TO_ORGANIZATION) {
                            /** @var AmosAdmin $adminModule */
                            $adminModule = AmosAdmin::instance();
                            if ($adminModule->getOrganizationModuleName() == 'organizzazioni') {
                                return $this->redirect(['/organizzazioni/profilo/update', 'id' => $modelId]);
                            }
                        } elseif ($returnType == InvitationsUtility::RETURN_TO_USER_PROFILE) {
                            return $this->redirect(['/admin/user-profile/update', 'id' => $modelId]);
                        }
                    }
                }
                
                return $this->redirect([
                    'index' . (Yii::$app->user->can('ADMIN') ? '-all' : ''),
                    'moduleName' => ($this->moduleName ? $this->moduleName : null),
                    'contextModelId' => ($this->contextModelId ? $this->contextModelId : null)
                ]);
            }
            // check if email is unique... if not i find this email in database, and i use this to create notification
            if ($invitationUser->validate('email')) {
                if (!$invitationUser->save()) {
                    $message = Module::t('amosinvitations', 'Error to save invitation email');
                    if ($actionView) {
                        Yii::$app->getSession()->addFlash('danger', $message);
                        return $this->render($actionView, [
                            'invitation' => $invitation,
                            'invitationUser' => $invitationUser,
                        ]);
                    }
                }
            } else {
                /** @var InvitationUser $invitationUserModel */
                $invitationUserModel = $this->invitationsModule->createModel('InvitationUser');
                
                /** @var InvitationUser $invitationUser */
                $invitationUser = $invitationUserModel::findOne(['email' => $invitationUser->email]);
            }
        }
        
        $invitation->invitation_user_id = $invitationUser->id;
        if ($invitation->load(Yii::$app->request->post()) && $invitation->validate()) {
            $invitation = $this->sendMailInvitation($invitation);
            if ($invitation->save()) {
                $message = Module::t('amosinvitations', 'Item sended');
                if ($actionView) {
                    Yii::$app->getSession()->addFlash('success', $message);
                    
                    $sessionUrl = Yii::$app->session->get(Module::beginCreateNewSessionKey());
                    if (!is_null($sessionUrl)) {
                        return $this->redirect($sessionUrl);
                    }
                    
                    if (!empty($this->returnTo)) {
                        $explode = explode('_', $this->returnTo);
                        if (count($explode) == 2) {
                            $returnType = $explode[0] . '_';
                            $modelId = $explode[1];
                            if ($returnType == InvitationsUtility::RETURN_TO_ORGANIZATION) {
                                /** @var AmosAdmin $adminModule */
                                $adminModule = AmosAdmin::instance();
                                if ($adminModule->getOrganizationModuleName() == 'organizzazioni') {
                                    return $this->redirect(['/organizzazioni/profilo/update', 'id' => $modelId]);
                                }
                            } elseif ($returnType == InvitationsUtility::RETURN_TO_USER_PROFILE) {
                                return $this->redirect(['/admin/user-profile/update', 'id' => $modelId]);
                            }
                        }
                    }
                    
                    return $this->redirect([
                        'index' . (Yii::$app->user->can('ADMIN') ? '-all' : ''),
                        'moduleName' => ($this->moduleName ? $this->moduleName : null),
                        'contextModelId' => ($this->contextModelId ? $this->contextModelId : null)
                    ]);
                }
            } else {
                $message = Module::t('amosinvitations', 'Item not sended, check data');
                if ($actionView) {
                    Yii::$app->getSession()->addFlash('danger', $message);
                    return $this->render($actionView, [
                        'invitation' => $invitation,
                        'invitationUser' => $invitationUser,
                    ]);
                }
            }
        } else if ($actionView) {
            return $this->render($actionView, [
                'invitation' => $invitation,
                'invitationUser' => $invitationUser,
            ]);
        }
        
        return $message;
    }
    
    /**
     * Updates an existing Invitation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        
        /** @var Invitation $invitationModel */
        $invitationModel = $this->invitationsModule->createModel('Invitation');
        
        /** @var Invitation $invitation */
        $invitation = $invitationModel::findOne($id);
        if (empty($invitation)) {
            throw new BadRequestHttpException();
        }
        $invitationUser = $invitation->invitationUser;
        
        if (Yii::$app->request->post()) {
            if (InvitationsUtility::checkUserAlreadyPresent($invitationUser->email)) {
                return $this->render('update', [
                    'invitation' => $invitation,
                    'invitationUser' => $invitationUser,
                ]);
            }
            if (!$invitation->send) {
                return $this->sendInvitation('update', $invitation, $invitationUser);
            } else {
                if ($invitation->load(Yii::$app->request->post())) {
                    $this->resend($invitation);
                    $sessionUrl = Yii::$app->session->get(Module::beginCreateNewSessionKey());
                    if (!is_null($sessionUrl)) {
                        return $this->redirect($sessionUrl);
                    } elseif (Yii::$app->user->can('ADMIN')) {
                        return $this->redirect([
                            'index-all',
                            'moduleName' => ($this->moduleName ? $this->moduleName : null),
                            'contextModelId' => ($this->contextModelId ? $this->contextModelId : null)
                        ]);
                    } else {
                        return $this->redirect([
                            'index',
                            'moduleName' => ($this->moduleName ? $this->moduleName : null),
                            'contextModelId' => ($this->contextModelId ? $this->contextModelId : null)
                        ]);
                    }
                }
            }
        }
        return $this->render('update', [
            'invitation' => $invitation,
            'invitationUser' => $invitationUser,
        ]);
    }
    
    /**
     * Deletes an existing Invitation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            //si può sostituire il  delete() con forceDelete() in caso di SOFT DELETE attiva
            //In caso di soft delete attiva e usando la funzione delete() non sarà bloccata
            //la cancellazione del record in presenza di foreign key quindi
            //il record sarà cancelleto comunque anche in presenza di tabelle collegate a questo record
            //e non saranno cancellate le dipendenze e non avremo nemmeno evidenza della loro presenza
            //In caso di soft delete attiva è consigliato modificare la funzione oppure utilizzare il forceDelete() che non andrà
            //mai a buon fine in caso di dipendenze presenti sul record da cancellare
            if ($model->delete()) {
                Yii::$app->getSession()->addFlash('success', Module::t('amosinvitations', 'Item deleted'));
            } else {
//                Yii::$app->getSession()->addFlash('danger', Module::t('amosinvitations', 'Item not deleted because of dependency'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::t('amosinvitations', 'Item not found'));
        }
        return $this->redirect([
            'index',
            'moduleName' => ($this->moduleName ? $this->moduleName : null),
            'contextModelId' => ($this->contextModelId ? $this->contextModelId : null)
        ]);
    }
    
    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel()
    {
        $importInvite = Html::button(Module::t('amosinvitations', 'Import invitations'), [
            'class' => 'btn btn-primary',
            'data-toggle' => 'modal',
            'data-target' => '#modalImport',
            'moduleName' => $this->moduleName,
            'contextModelId' => $this->contextModelId
        ]);
        
        $session = Yii::$app->session;
        if ($session->has(AmosAdmin::GOOGLE_CONTACTS_NOT_PLATFORM)) {
            $contactsNotPlatform = $session->get(AmosAdmin::GOOGLE_CONTACTS_NOT_PLATFORM);
            if (!empty($contactsNotPlatform)) {
                $inviteFromGoogle = Html::a(AmosIcons::show('google') . '&nbsp;' . Module::t('amosinvitations', '#invite_google_btn'),
                    'invite-google', ['class' => 'btn btn-primary']);
            }
        }
        
        Yii::$app->view->params['createNewBtnParams'] = [
            'urlCreateNew' => ['create', 'moduleName' => $this->moduleName, 'contextModelId' => $this->contextModelId],
            'createNewBtnLabel' => AmosAdmin::t('amosinvitations', '#create_new_invite'),
            'layout' => "{buttonCreateNew}" . $importInvite . (isset($inviteFromGoogle) ? $inviteFromGoogle : '')
        ];
    }
}
