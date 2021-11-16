<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\operators\base
 */


namespace backend\modules\operators\controllers\base;

use app\modules\cms\utility\RegisterUser;
use backend\modules\operators\models\ImportOperatorForm;
use backend\modules\registeredusers\models\search\RegisteredUserSearch;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\user\User;
use open20\amos\admin\models\UserProfile;
use backend\modules\operators\models\Operator;
use backend\modules\operators\models\search\OperatorSearch;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\utilities\Email;
use open20\amos\porting\console\models\AuthAssignment;
use preference\userprofile\models\PreferenceUserTargetAttribute;
use preference\userprofile\models\Tag;
use preference\userprofile\utility\TargetTagUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;


/**
 * Class OperatorController
 * OperatorController implements the CRUD actions for Operator model.
 *
 * @property \backend\modules\operators\models\Operator $model
 * @property \backend\modules\operators\models\search\OperatorSearch $modelSearch
 *
 * @package backend\modules\operators\base
 */
class OperatorController extends CrudController

{


    /**
     * @var string $layout
     */

    public $layout = 'main';

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
                            'import-operator',
                            'add-frontend-user-hidden',
                        ],
                        'roles' => ['OPERATOR_CREATE']
                    ]
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

    public function init()
    {


        $this->setModelObj(new Operator());

        $this->setModelSearch(new OperatorSearch());


        $this->setAvailableViews([

            'grid' => [

                'name' => 'grid',

                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),

                'url' => '?currentView=grid'

            ],


        ]);


        parent::init();

        $this->setUpLayout('bootstrap-italia-layout-no-sidebar');

    }


    /**
     * Lists all Operator models.
     * @return mixed
     */

    public function actionIndex($layout = NULL)

    {

        Url::remember();
        $ms = $this->modelSearch;
        $ms->orderAttribute = 'created_at';
        $ms->orderType = 'DESC';
        $this->setDataProvider($ms->search(Yii::$app->request->getQueryParams()));
//        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $this->setUpLayout('bootstrap-italia-layout-no-sidebar');
        return parent::actionIndex($layout);

    }


    /**
     * Displays a single Operator model.
     * @param integer $id
     * @return mixed
     */

    public function actionView($id)

    {

        $this->model = $this->findModel($id);


        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {

            return $this->redirect(['view', 'id' => $this->model->id]);

        } else {

            return $this->render('view', ['model' => $this->model]);

        }

    }


    /**
     * Creates a new Operator model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $this->setUpLayout('form');
        
        $this->model = new Operator();
        $operatore = Operator::findOne(\Yii::$app->user->identity->profile->id);

        if (!empty($operatore->pc_structure_id)) {
            $this->model->pc_structure_id = $operatore->pc_structure_id;
            $structure = $operatore->pc_structure_id;
        }

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            if (!empty(User::find()->andWhere(['email' => $this->model->email])->one())) {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Email presente già presente a sistema'));
                $this->model->addError('email', 'Email presente già presente a sistema');
            } 

            if (!empty(UserProfile::find()->andWhere(['codice_fiscale' => $this->model->codice_fiscale])->one())) {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Codice fiscale presente a sistema'));
                $this->model->addError('codice_fiscale', 'Codice fiscale presente a sistema');
            }

            if ( !$this->model->hasErrors()) {

                $newUser = AmosAdmin::getInstance()->createNewAccount(
                    $this->model->nome,
                    $this->model->cognome,
                    $this->model->email,
                    true,
                    true
                );

                /**
                 * If $newUser is false the user is not created
                 */
                if (!empty($newUser) || !isset($newUser['error'])) {
                    if (!empty($newUser['user']->id)) {
                        $ruolo = \Yii::$app->authManager->getRole($this->model->role);
                        \Yii::$app->authManager->assign($ruolo, $newUser['user']->id);
                        Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                        $struttura = $this->model->pc_structure_id;
                        $model = \backend\modules\operators\models\Operator::findOne(['user_id' => $newUser['user']->id]);
                        $model->pc_structure_id = $struttura;
                        $model->codice_fiscale = $this->model->codice_fiscale;
                        $model->save(false);

                        // Se creato correttamente all'operatore vado ad aggiungere il ruolo anche per operatore di frontend
                        if ($this->model->frontend_role_flag) {
                            $op = UserProfile::findOne(['id' => $model->id]);
                            $this->addUserFrontedRole($op);
                            $this->sendFrontendRuleMail($op);
                        }

                        return $this->redirect(['index']);
                    }                    
                } else {
                    Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
                }
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
            'structure' => !empty($structure) ? $structure : NULL,
        ]);
    }

    /**
     *
     * @param UserProfile $operator
     */
    private function addUserFrontedRole($operator)
    {
        $roleArray = Yii::$app->authManager->getRolesByUser($operator->user->id);
        // SE non ha il ruolo di utente registrato in frontend allora vado ad abilitare il targhet cittadino
        if (!(isset($roleArray['PC_REGISTERD_USER']) && !empty($roleArray['PC_REGISTERD_USER']))){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                /** @var Tag $tag */
                $tag = TargetTagUtility::getTargetByKey('cittadino');
                $interest_classname = 'simple-choice';
                UserInterestTagUtility::saveRegisteredUserInterestTag($operator, $interest_classname, $tag);

                // CREO gli attributi all'utente su ogni target anche se vuoti!

                $uta = new PreferenceUserTargetAttribute();
                $uta->sendEmailValidationComunication = false;
                $uta->email = $operator->user->email;
                $uta->validated_email_flag = true;
                $uta->phone = null;
                $uta->validated_phone_flag = false;
                $uta->target_code = TargetTagUtility::getTargetByKey('cittadino')->codice;
                $uta->user_id = $operator->user->id;
                $uta->save(false);

                $uta->validated_email_flag = true;
                $uta->save(false);

                $uta = new PreferenceUserTargetAttribute();
                $uta->sendEmailValidationComunication = false;
                $uta->email = null;
                $uta->validated_email_flag = false;
                $uta->phone = null;
                $uta->validated_phone_flag = false;
                $uta->target_code = TargetTagUtility::getTargetByKey('impresa')->codice;
                $uta->user_id = $operator->user->id;
                $uta->save(false);

                $uta = new PreferenceUserTargetAttribute();
                $uta->sendEmailValidationComunication = false;
                $uta->email = null;
                $uta->validated_email_flag = false;
                $uta->phone = null;
                $uta->validated_phone_flag = false;
                $uta->target_code = TargetTagUtility::getTargetByKey('enteeoperatore')->codice;
                $uta->user_id = $operator->user->id;
                $uta->save(false);

                $frontendRole = Yii::$app->authManager->getRole('PC_REGISTERD_USER');
                Yii::$app->authManager->assign($frontendRole, $operator->user->id);

                $transaction->commit();
                Yii::$app->getSession()->addFlash('success','All\'utente è stato assegnato il ruolo per accedere in front-end');
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->addFlash('danger','Assegnazione del ruolo di front-end fallita: ' . $e->getMessage());
                throw $e;
            }
        } else {
            Yii::$app->getSession()->addFlash('warning','L\'utente ha già il ruolo per accedere in frontend');
        }

    }

    /**
     * Updates an existing Operator model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id, $action = null)

    {

        $this->setUpLayout('form');
        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar', []);
        $this->model = $this->findModel($id);

        if (\Yii::$app->user->can('PC_STRUCTURE_EDITOR') || \Yii::$app->user->can('PC_STRUCTURE_ADMIN')|| \Yii::$app->user->can('PC_STRUCTURE_APPROVER')) {
            $operatore=Operator::findOne(\Yii::$app->user->identity->profile->id);
            if(!empty($operatore->pc_structure_id)){
                $structure=$operatore->pc_structure_id;
            }
        }

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            if ($this->model->save()) {

                $ruolo = \Yii::$app->authManager->getRole($this->model->role);
                $ruoli = \Yii::$app->authManager->getRolesByUser($this->model->user_id);

                if (!array_key_exists($this->model->role, $ruoli)) {
                    $prevPCRole=$this->model->getPCRole();
                    if(!empty($prevPCRole)){
                        \Yii::$app->authManager->revoke($prevPCRole, $this->model->user_id);
                    }

                    \Yii::$app->authManager->assign($ruolo, $this->model->user_id);
                    if ($action == 'imp') {
                        UserProfileUtility::sendCredentialsMail($this->model);
                    }

                    // cambio di ruolo all'operatore...
                    DashboardUtility::resetDashboardsByUser($this->model->user_id);
                }

                // Se aggiornato correttamente all'operatore vado ad aggiungere il ruolo anche per operatore di frontend
                if ($this->model->frontend_role_flag) {
                    $op = UserProfile::findOne(['id' => $this->model->id]);
                    $this->addUserFrontedRole($op);
                    $this->sendFrontendRuleMail($op);
                }

                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));

                return $this->redirect(['update', 'id' => $this->model->id]);

            } else {

                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));

            }

        }
        return $this->render('update', [

            'model' => $this->model,

            'fid' => NULL,

            'dataField' => NULL,

            'dataEntity' => NULL,

            'structure' => !empty($structure) ? $structure : NULL,

        ]);

    }

    private function sendFrontendRuleMail($model)
    {
        try {
            $subjectView = '@backend/mail/user/rule-fe-notification-subject';
            $contentView = '@backend/mail/user/rule-fe-notification';
            $subject = Email::renderMailPartial($subjectView, ['profile' => $model], $model->user->id);
            $mail = Email::renderMailPartial($contentView, ['profile' => $model], $model->user->id);
            return Email::sendMail(Yii::$app->params['supportEmail'], [$model->user->email], $subject, $mail, []);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
        return false;
    }

    public function actionImportOperator()
    {
        $this->setUpLayout('form');
        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar', []);

        $model = new ImportOperatorForm();

        $ms = new RegisteredUserSearch();
        $ms->orderAttribute = 'created_at';
        $ms->orderType = 'DESC';
        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $ms->search([]);
        $dataProvider->pagination = false;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->redirect(['update', 'id' => $model->id, 'action' => 'imp']);
        }

        return $this->render('import_operator', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Deletes an existing Operator model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    public function actionDelete($id)

    {

        $this->model = $this->findModel($id);

        if ($this->model) {

            $this->model->delete();

            if (!$this->model->hasErrors()) {

                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));

            } else {

                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));

            }

        } else {

            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));

        }

        return $this->redirect(['index']);

    }

    /**
     *
     */
    public function actionAddFrontendUserHidden()
    {
        die('.');

        $utenti = [
            [
                'nome' => 'Michele T1',
                'cognome' => 'Zucchini T1',
                'email' => 'michele.zucchini+T1@open20.it',
                'codice_fiscale' => 'ZCCMHL...',
                'password' => 'Password2021',
            ],
            [
                'nome' => 'Michele T2',
                'cognome' => 'Zucchini T2',
                'email' => 'michele.zucchini+T2@open20.it',
                'codice_fiscale' => 'ZCCMHL...',
                'password' => 'Password2021',
            ],
        ];

        echo('Creazione di: <br>');
        VarDumper::dump($utenti, 3, true);
        echo('<hr>');

        foreach ($utenti as $utente) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $newUser = AmosAdmin::getInstance()->createNewAccount(
                    $utente['nome'],
                    $utente['cognome'],
                    $utente['email'],
                    true
                );

                if ((!empty($newUser) || !isset($newUser['error'])) && !empty($newUser['user']->id)) {
                    $up = UserProfile::findOne(['user_id' => $newUser['user']->id]);
                    $up->codice_fiscale = $utente['cognome'];
                    $up->save(false);

                    $user = $up->user;
                    $user->password_hash = \Yii::$app->security->generatePasswordHash($utente['password']);
                    $user->save(false);

                    $transaction->commit();
                    // questa funziona fa il commit!
                    $this->addUserFrontedRole($up, $transaction);

                    echo('Creato utente '.$utente['email']. ' User:' . $user->id . ' UserProfile:' . $up->id);
                    echo('<hr>');

                } else {

                    echo('Errore nel create M:' . $utente['email'] . ' e: ' .$newUser['error']);
                    echo('<hr>');
                    VarDumper::dump($newUser, 3,true);
                    $transaction->rollBack();
                }

            } catch (\Exception $e) {
                echo('Errore nel create M:' . $utente['email']);
                echo('<br>');
                echo($e->getMessage());
                echo('<br>');
                echo($e->getFile()); echo(' '); echo($e->getLine());
                echo('<br>Trace:<br>');
                VarDumper::dump($e->getTrace(), 3,true);
                echo('<hr>');
                $transaction->rollBack();

                throw $e;
            }
        }

        echo('<hr>');
        echo('FINE');
        echo('<hr>');

    }

}
