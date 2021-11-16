<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\registeredusers\base
 */


namespace backend\modules\registeredusers\controllers\base;


use Yii;
use DateTime;
use Throwable;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use open20\amos\core\helpers\T;
use preference\userprofile\models\Tag;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use preference\userprofile\models\UserChannel;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\module\BaseAmosModule;
use backend\modules\registeredusers\models\Operator;
use open20\amos\core\controllers\CrudController;
use backend\modules\registeredusers\models\RegisteredUser;
use open20\amos\porting\console\models\AuthAssignment;
use backend\modules\registeredusers\models\search\RegisteredUserSearch;

use yii\base\Model;


/**
 * Class OperatorController
 * OperatorController implements the CRUD actions for Operator model.
 *
 * @property \backend\modules\registeredusers\models\Operator $model
 * @property \backend\modules\registeredusers\models\search\registeredusersearch $modelSearch
 *
 * @package backend\modules\registeredusers\base
 */
class RegisteredUserController extends CrudController

{


    /**
     * @var string $layout
     */

    public $layout = 'main';


    public function init()
    {

        $this->setModelObj(new RegisteredUser());

        $this->setModelSearch(new RegisteredUserSearch());

        $this->setAvailableViews([

            'grid' => [

                'name' => 'grid',

                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),

                'url' => '?currentView=grid'

            ],


        ]);


        parent::init();

    //        \Yii::$app->params['bsVersion'] = '4.x';
    //        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
    //        $this->setUpLayout('bootstrap-italia-layout-with-sidebar', []);


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
        $this->setUpLayout('bootstrap-italia-layout-no-sidebar');
        \Yii::$app->view->params['createNewBtnParams'] = ['layout' => ''];
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
        // in quanto non deve essere possibile la creazione di un utente dal lato backend
        throw new ForbiddenHttpException('404 Page not found.');

        $this->setUpLayout('form');
        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar', []);

        $this->model = new RegisteredUser();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            $newUser = AmosAdmin::getInstance()->createNewAccount(
                $this->model->nome, $this->model->cognome, $this->model->email, true, true
            );

            /**
             * If $newUser is false the user is not created
             */
            if ($newUser || !isset($newUser['error'])) {
                $ruolo = \Yii::$app->authManager->getRole('PC_REGISTERD_USER');
                \Yii::$app->authManager->assign($ruolo, $newUser['user']->id);
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));

                $transaction = \Yii::$app->db->beginTransaction();
                try {

                    $userProfile = UserProfile::findOne(['user_id' => $newUser['user']->id]);
                    foreach ($_POST['topic'] as $topic => $id) {
                        $tag = Tag::findOne(['id' => $topic]);
                        $interest_classname = 'simple-choice';
                        \preference\userprofile\utility\UserInterestTagUtility::saveRegisteredUserInterestTag($userProfile, $interest_classname, $tag);
                    }
                    foreach ($_POST['target'] as $target => $id) {
                        $tag = Tag::findOne(['id' => $target]);
                        $interest_classname = 'simple-choice';
                        \preference\userprofile\utility\UserInterestTagUtility::saveRegisteredUserInterestTag($userProfile, $interest_classname, $tag);
                    }
                    foreach ($_POST['TopicChannel'] as $topic => $channels) {
                        $tag = Tag::findOne(['id' => $topic]);
                        foreach ($channels as $channel) {
                            if (!empty($tag)) {
                                \preference\userprofile\utility\UserInterestTagUtility::addSinglePreferenceTopicChannel($newUser['user']->id, $tag, $channel);
                            }
                        }
                    }
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }


                return $this->redirect(['update', 'id' => $newUser['user']->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
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
     * Updates an existing Operator model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id)
    {

        $this->setUpLayout('form');
        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar', []);

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            $transaction = \Yii::$app->db->beginTransaction();
            try {
                \preference\userprofile\utility\TopicTagUtility::destroyAllChannelsByUser($this->model->user_id);
                \preference\userprofile\utility\TopicTagUtility::destroyAllInterestTag($this->model->user_id);

                $userProfile = UserProfile::findOne(['id' => $this->model->id]);
                foreach ($_POST['topic'] as $topic => $id) {
                    $tag = Tag::findOne(['id' => $topic]);
                    $interest_classname = 'simple-choice';
                    \preference\userprofile\utility\UserInterestTagUtility::saveRegisteredUserInterestTag($userProfile, $interest_classname, $tag);
                }
                foreach ($_POST['target'] as $target => $id) {
                    $tag = Tag::findOne(['id' => $target]);
                    $interest_classname = 'simple-choice';
                    \preference\userprofile\utility\UserInterestTagUtility::saveRegisteredUserInterestTag($userProfile, $interest_classname, $tag);
                }
                foreach ($_POST['TopicChannel'] as $topic => $channels) {
                    $tag = Tag::findOne(['id' => $topic]);
                    foreach ($channels as $channel) {
                        if (!empty($tag)) {
                            \preference\userprofile\utility\UserInterestTagUtility::addSinglePreferenceTopicChannel($this->model->user_id, $tag, $channel);
                        }
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }

            if ($this->model->save()) {

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

}
