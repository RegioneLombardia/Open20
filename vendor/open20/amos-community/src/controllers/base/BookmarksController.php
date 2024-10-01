<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\controllers\base
 */

namespace open20\amos\community\controllers\base;

use open20\amos\community\models\base\CommunityUserMm;
use open20\amos\community\models\Community;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\notificationmanager\base\BuilderFactory;
use Yii;
use open20\amos\community\models\Bookmarks;
use open20\amos\community\models\search\BookmarksSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;


/**
 * Class BookmarksController
 * BookmarksController implements the CRUD actions for Bookmarks model.
 *
 * @property \open20\amos\community\models\Bookmarks $model
 * @property \open20\amos\community\models\search\BookmarksSearch $modelSearch
 *
 * @package open20\amos\community\controllers\base
 */
class BookmarksController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new Bookmarks());
        $this->setModelSearch(new BookmarksSearch());

        $this->setAvailableViews([
            /*'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],*/
            'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),
                'url' => '?currentView=list'
            ],
            /*'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Icons')),           
                'url' => '?currentView=icon'
            ],
            'map' => [
                'name' => 'map',
                'label' => AmosIcons::show('map') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Map')),      
                'url' => '?currentView=map'
            ],
            'calendar' => [
                'name' => 'calendar',
                'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
                                      //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
                'label' => AmosIcons::show('calendar') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Calendari')),                                            
                'url' => '?currentView=calendar'
            ],*/
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all Bookmarks models.
     * @return mixed
     */
    public function actionIndex($id, $layout = NULL)
    {
        Url::remember();
        $query = CommunityUserMm::find()->where(['community_id' => $id, 'user_id' => \Yii::$app->user->id, 'deleted_at' => null, 'status' => CommunityUserMm::STATUS_ACTIVE])->one();
        if (is_null($query)) $query = Community::find()->where(['id' => $id])->one();

        $this->setParametro($query);
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single Bookmarks model.
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
     * Creates a new Bookmarks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $this->setUpLayout('form');
        $this->model = new Bookmarks();
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            $this->model->status = (!is_null(Yii::$app->request->post('status'))) ?
                Bookmarks::BOOKMARKS_STATUS_PUBLISHED :
                Bookmarks::BOOKMARKS_STATUS_DRAFT;
            $this->model->data_pubblicazione = Yii::$app->getFormatter()->asDate('now', 'yyyy-MM-dd');
            $this->model->creatore_id = Yii::$app->user->id;

            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'community' => Community::findOne($id),
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new Bookmarks model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax()
    {
        #$this->setUpLayout('form');
        $this->model = new Bookmarks();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->data_pubblicazione = Yii::$app->getFormatter()->asDate('now', 'yyyy-MM-dd');
            $this->model->creatore_id = Yii::$app->user->id;
            if ($this->model->save()) {
                $this->model->status = Bookmarks::BOOKMARKS_STATUS_PUBLISHED;
                $this->model->save(false);
                $community = Community::findOne($this->model->community_id);
                $data = $community->getSomeLinks();
                return $this->renderPartial('@vendor/open20/amos-community/src/widgets/graphics/views/parts/_lastLinks', [
                    'model' => $community,
                    'data' => $data
                ]);
            } else {
                return false;
            }
        }

        return json_encode($this->model->getErrors());
    }

    /**
     * Updates an existing Bookmarks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->status === Bookmarks::BOOKMARKS_STATUS_PUBLISHED) {
                $this->model->data_pubblicazione = Yii::$app->getFormatter()->asDate('now', 'yyyy-MM-dd');
            }
            elseif($this->model->status === Bookmarks::BOOKMARKS_STATUS_TOVALIDATE){
                //funzionamento standard in notify
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
            'community' => Community::findOne($this->model->community_id),
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing Bookmarks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $community, $redirect=null)
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
        if($redirect){
            return $this->redirect(['bookmarks/index', 'id' => $community]);
        }
        else{
            return $this->redirect(['join/open-join', 'id' => $community]);
        }
    }
}
