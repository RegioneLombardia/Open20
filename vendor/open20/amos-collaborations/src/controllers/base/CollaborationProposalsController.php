<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\controllers\base
 */

namespace open20\amos\collaborations\controllers\base;

use open20\amos\admin\models\UserProfile;
use open20\amos\collaborations\models\CollaborationIprStatusMm;
use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\collaborations\models\search\CollaborationProposalsSearch as CollaborationProposalsSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * Class CollaborationProposalsController *  implements the CRUD actions for CollaborationProposalsSearch model.
 *
 * @property CollaborationProposals $model
 * @property-write mixed $viewParams
 * @property CollaborationProposalsSearch $modelSearch
 *
 * @package open20\amos\collaborations\controllers\base
 */
class CollaborationProposalsController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * Module
     * @var null
     */
    public $collaborationProposalsModule = null;

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->setModelObj(new CollaborationProposals());
        $this->setModelSearch(new CollaborationProposalsSearch());

        $this->setAvailableViews([
            'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),
                'url' => '?currentView=list'
            ],
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all CollaborationProposalsSearch models.
     * @return string
     * @throws InvalidConfigException|NotFoundHttpException
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();

        $this->setViewParams('index');

        if (Yii::$app->user->isGuest) {
            $this->setDataProvider($this->modelSearch->searchPublishedGuest(Yii::$app->request->getQueryParams()));
        } else {
            $this->setDataProvider($this->modelSearch->searchPublished(Yii::$app->request->getQueryParams()));
        }

        return parent::actionIndex($layout);
    }

    /**
     * Displays a single CollaborationProposalsSearch model.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        }

        if (!$this->canView()) {
//            throw new UnauthorizedHttpException(Yii::t('amoscollaborations', 'Non sei autorizzato a visualizzare questo contenuto'));
                return $this->redirect(Yii::$app->params['linkConfigurations']['loginLinkCommon']);
        }

        return $this->render('view', ['model' => $this->model]);
    }

    /**
     * Creates a new CollaborationProposalsSearch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new CollaborationProposals();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            
            $doSave = true;
            if (($this->model->isNewRecord) && (!empty($this->model->status))
                && ($this->model->status != CollaborationProposals::STATUS_DRAFT)) {
                $statusOrig = $this->model->status;
                $this->model->status = CollaborationProposals::STATUS_DRAFT;
                if (!$this->model->save()) {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
                    $doSave = false;
                }
                $this->model->status = $statusOrig;
            }
            if ($doSave) {
                if ($this->model->save()) {
                    // $this->model->convertIdEenToUppercase();
                    // print_r($this->model->toArray());die;
                    $this->model->saveMmTables();
                    // $this->model->saveGoals();
                    Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                    return $this->redirect(['update', 'id' => $this->model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
                }
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new CollaborationProposalsSearch model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return false|string
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new CollaborationProposals();

        if (Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
//Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
//Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->renderAjax('_formAjax', [
            'model' => $this->model,
            'fid' => $fid,
            'dataField' => $dataField
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);
        if (!$this->model->userCanUpdate()) {
            throw new ForbiddenHttpException();
        }
        $this->model->loadMmTablesValues();
        // $this->model->loadGoals();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $tagValues = explode(',', $this->model->tagValues);
            // $tagValues = ArrayHelper::merge($tagValues, $this->model->tagValuesGoal);
            $this->model->tagValues = $tagValues;


            if ($this->model->save()) {
                // $this->model->convertIdEenToUppercase();
                $this->model->saveMmTables();
                // $this->model->saveGoals();
                $this->model->updateRelatedExpressionsOfInterest($id, $this->model->title);
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing CollaborationProposalsSearch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            if (!$this->model->userCanDelete()) {
                throw new ForbiddenHttpException();
            }
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(Url::previous());
    }

    /**
     * Set view params for defined action
     * @param $action
     * @return void
     * @throws InvalidConfigException
     */
    public function setViewParams($action)
    {
        // index
        if ($action == 'index') {
            if (!Yii::$app->user->isGuest) {
                $countProposals = CollaborationProposals::find()
                    ->andWhere(['status' => CollaborationProposals::STATUS_PUBLISHED])
                    ->andWhere(['>=', 'end_date', date('Y-m-d')])
                    ->count();
            } else {
                $countProposals = CollaborationProposals::find()
                    ->andWhere(['status' => CollaborationProposals::STATUS_PUBLISHED])
                    ->andWhere(['>=', 'end_date', date('Y-m-d')])
                    ->andWhere(['visible_to_guest' => CollaborationProposals::VISIBLE_TO_GUEST])
                    ->count();
            }

            if (empty($countProposals) || !is_numeric($countProposals)) {
                $countProposals = '';
            }

            $this->view->params['subTitleSection'] = \yii\helpers\Html::tag('p',
                Yii::t('amoscollaborations', 'Naviga fra le {x} proposte di collaborazione promosse sulla piattaforma Open 2.0', ['x' => $countProposals > 1 ? $countProposals : ''])
            );

            if (!Yii::$app->user->isGuest) {
                $userProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
                $this->view->params['subTitleSection'] .= \yii\helpers\Html::tag('p',
                    Yii::t('amoscollaborations', 'Ti ricordiamo che puoi definire i temi di tuo interesse nel tuo ') .
                    (!empty($userProfile) ? Html::a(Yii::t('amoscollaborations', 'profilo'), ['/amosadmin/user-profile/update', 'id' => $userProfile->id, '#' => 'tab-tags']) : Yii::t('amoscollaborations', 'profilo')) . '.');

                $this->view->params['labelLinkAll'] = Yii::t('amoscollaborations', 'Proposte di mio interesse');
                $this->view->params['urlLinkAll'] = '/collaborations/collaboration-proposals/own-interest';
                $this->view->params['titleLinkAll'] = Yii::t('amoscollaborations', 'Visualizza le proposte di mio interesse');
            }
        }
        // to-publish
        else if ($action == 'toPublish') {
            $this->view->params['titleSection'] = Yii::t('amoscollaborations', 'Proposte da pubblicare');
            $this->view->params['labelLinkAll'] = Yii::t('amoscollaborations', 'Proposte di mio interesse');
            $this->view->params['urlLinkAll'] = '/collaborations/collaboration-proposals/own-interest';
            $this->view->params['titleLinkAll'] = Yii::t('amoscollaborations', 'Visualizza le proposte di mio interesse');
            $this->view->params['subTitleSection'] = \yii\helpers\Html::tag('p', Yii::t('amoscollaborations', 'Elenco delle proposte di collaborazione da pubblicare'));
        }
        // created-by-me
        else if ($action == 'createdByMe') {
            $this->view->params['titleSection'] = Yii::t('amoscollaborations', 'Proposte create da me');
            $this->view->params['labelLinkAll'] = Yii::t('amoscollaborations', 'Proposte di mio interesse');
            $this->view->params['urlLinkAll'] = '/collaborations/collaboration-proposals/own-interest';
            $this->view->params['titleLinkAll'] = Yii::t('amoscollaborations', 'Visualizza le proposte di mio interesse');
        }
        // own-interest
        else if ($action == 'ownInterest') {
            $this->view->params['titleSection'] = Yii::t('amoscollaborations', 'Proposte di mio interesse');
            $this->view->params['labelLinkAll'] = Yii::t('amoscollaborations', 'Tutte le proposte attive');
            $this->view->params['urlLinkAll'] = '/collaborations/collaboration-proposals/index';
            $this->view->params['titleLinkAll'] = Yii::t('amoscollaborations', 'Visualizza la lista di tutte le proposte attive');
            $this->view->params['subTitleSection'] = \yii\helpers\Html::tag('p', Yii::t('amoscollaborations', 'Elenco delle proposte di collaborazione di mio interesse pubblicate'));
        }
        // archived
        // else if ($action == 'archived') {
        //     $this->view->params['titleSection'] = Yii::t('amoscollaborations', 'Proposte archiviate');
        //     $this->view->params['labelLinkAll'] = Yii::t('amoscollaborations', 'Proposte di mio interesse');
        //     $this->view->params['urlLinkAll'] = '/collaborations/collaboration-proposals/own-interest';
        //     $this->view->params['titleLinkAll'] = Yii::t('amoscollaborations', 'Visualizza le proposte di mio interesse');
        //     $this->view->params['subTitleSection'] = \yii\helpers\Html::tag('p', Yii::t('amoscollaborations', 'Elenco delle proposte di collaborazione archiviate'));
        // }
        // closed
        else if ($action == 'closed') {
            $this->view->params['titleSection'] = Yii::t('amoscollaborations', 'Proposte chiuse');
            $this->view->params['labelLinkAll'] = Yii::t('amoscollaborations', 'Proposte di mio interesse');
            $this->view->params['urlLinkAll'] = '/collaborations/collaboration-proposals/own-interest';
            $this->view->params['titleLinkAll'] = Yii::t('amoscollaborations', 'Visualizza le proposte di mio interesse');
            $this->view->params['subTitleSection'] = \yii\helpers\Html::tag('p', Yii::t('amoscollaborations', 'Elenco delle proposte di collaborazione chiuse'));
        }
        // manage
        else if ($action == 'manage') {
            $this->view->params['titleSection'] = Yii::t('amoscollaborations', 'Amministra proposte');
            $this->view->params['labelLinkAll'] = Yii::t('amoscollaborations', 'Proposte di mio interesse');
            $this->view->params['urlLinkAll'] = '/collaborations/collaboration-proposals/own-interest';
            $this->view->params['titleLinkAll'] = Yii::t('amoscollaborations', 'Visualizza le proposte di mio interesse');
        }
        //to validate
        else if ($action == 'toValidate') {
            $this->view->params['titleSection'] = Yii::t('amoscollaborations',
                '#proposals_to_validate');
            $this->view->params['labelLinkAll'] = Yii::t('amoscollaborations',
                'Proposte di mio interesse');
            $this->view->params['urlLinkAll'] =
                '/collaborations/collaboration-proposals/own-interest';
            $this->view->params['titleLinkAll'] = Yii::t('amoscollaborations',
                '#list_of_collaboration_proposals_to_validate');
            $this->view->params['subTitleSection'] = Html::tag('p',
                Yii::t('amoscollaborations',
                    '#list_of_collaboration_proposals_to_validate')
            );
        }
        // refused
        else if ($action == 'refused') {
            $this->view->params['titleSection'] = Yii::t('amoscollaborations',
                '#many_refused');
            $this->view->params['labelLinkAll'] = Yii::t('amoscollaborations',
                'Proposte di mio interesse');
            $this->view->params['urlLinkAll'] =
                '/collaborations/collaboration-proposals/own-interest';
            $this->view->params['titleLinkAll'] = Yii::t('amoscollaborations',
                '#list_of_collaboration_proposals_refused');
            $this->view->params['subTitleSection'] = Html::tag('p',
                Yii::t('amoscollaborations',
                    '#list_of_collaboration_proposals_refused')
            );
        }
    }

    /**
     * Check if user can view proposal detail
     * @return bool
     */
    public function canView()
    {
        if (Yii::$app->user->isGuest) {
            if ($this->model->visible_to_guest == CollaborationProposals::NOT_VISIBLE_TO_GUEST || $this->model->status == CollaborationProposals::STATUS_DRAFT) {
                return false;
            }
        } else {
            if ($this->model->status == CollaborationProposals::STATUS_DRAFT && !(Yii::$app->user->can('COLLABORATIONS_ADMINISTRATOR') || Yii::$app->user->can('COLLABORATIONS_STAFF'))) {
                return false;
            }
        }

        return true;
    }

}
