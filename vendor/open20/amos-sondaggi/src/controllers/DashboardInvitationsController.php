<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 11/03/2020
 * Time: 12:19
 */

namespace open20\amos\sondaggi\controllers;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\utility\SondaggiUtility;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiInvitations;
use open20\amos\sondaggi\models\search\SondaggiInvitationsSearch;
use open20\amos\organizzazioni\models\ProfiloGroups;
use open20\amos\tag\models\Tag;
use Yii;

class DashboardInvitationsController extends CrudController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge([],
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'index'
                            ],
                            'roles' => ['@']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'renderSearchAjax',
                                'getValues'
                            ],
                            'roles' => ['AMMINISTRAZIONE_SONDAGGI']
                        ],
                        [
                            'allow' => true,
                            'actions' => [

                            ],
                            'roles' => ['SONDAGGI_READ']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'hour-by-date'
                            ]
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
     * @var string $layout
     */
    public $layout = 'list';
    public
        $moduleCwh,
        $scope;

    /**
     * @var AmosEvents $sondaggiModule
     */
    public $sondaggiModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {

        $this->sondaggiModule = AmosSondaggi::instance();

        $this->setModelObj(new SondaggiInvitations());
        $this->setModelSearch(new SondaggiInvitationsSearch());

//        EventsAsset::register(\Yii::$app->view);

        $this->scope     = null;
        $this->moduleCwh = \Yii::$app->getModule('cwh');

        if (!empty($this->moduleCwh)) {
            $this->scope = $this->moduleCwh->getCwhScope();
        }

        $this->setAvailableViews([
            'list' => [
                'name' => 'list',
                'label' => AmosSondaggi::t('amossondaggi', '{iconaLista}'.Html::tag('p', 'Lista'),
                    [
                    'iconaLista' => AmosIcons::show('view-list')
                ]),
                'url' => '?currentView=list'
            ],
            'grid' => [
                'name' => 'grid',
                'label' => AmosSondaggi::t('amossondaggi',
                    '{tableIcon}'.Html::tag('p', AmosSondaggi::t('amossondaggi', 'Table')),
                    [
                    'tableIcon' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ]
        ]);

        parent::init();

        //    \Yii::$app->params['bsVersion']                    = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        \Yii::$app->view->params['showSidebarForm']        = true;
        $this->setUpLayout('form');
    }

    /**
     * @param null $layout
     * @return string|\yii\web\Response
     */
    public function actionIndex($idSondaggio, $layout = null)
    {
        Url::remember();
        $this->setUrl($url);
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setListViewsParams($idSondaggio);
//        return parent::actionIndex($layout); // TODO sistemare questo punto cambiando totalmente la action in quanto non compatibile con gli standard di PHP 7
        \Yii::$app->getView()->params['showSidebarForm'] = true;
        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio));
  $this->setCurrentView($this->getAvailableView('grid'));
        return parent::actionIndex('form');
    }

    /**
     * Creates a new SondaggiInvitations model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @param string|null $url
     * @return string
     */
    public function actionCreate($idSondaggio, $url = null)
    {
        $this->setUpLayout('form');
        $this->model = new SondaggiInvitations();
        $this->model->sondaggi_id = $idSondaggio;
        $this->model->type = SondaggiInvitationsSearch::SEARCH_FILTER;
        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio), $this->model->id);
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->invited = 0;
            $this->model->query = SondaggiInvitationsSearch::searchOrganizations(Yii::$app->request->post()['SondaggiInvitations'])->query->createCommand()->rawSql;
            $this->model->count = SondaggiInvitationsSearch::searchOrganizations(Yii::$app->request->post()['SondaggiInvitations'])->query->count();
            if($this->model->save()){
                \Yii::$app->session->addFlash('success', AmosSondaggi::t('amossondaggi', "#invitation_list_created"));
            }
            else  {
                \Yii::$app->session->addFlash('danger', AmosSondaggi::t('amossondaggi', "#invitation_list_error"));
            }
            return $this->redirect(['/sondaggi/dashboard-invitations/index', 'idSondaggio'=> $idSondaggio, 'url' => $url ]);

        }

        return $this->render('create',
            [
                'model' => $this->model,
                'url' => ($url) ? $url : null,
            ]);
    }

    /**
     * Updates an existing SondaggiInvitations model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @param null $url
     * @return string|\yii\web\Response
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id, $url = null)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);
        $idSondaggio = $this->model->sondaggi_id;
        $validazioni = [];
        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio), $this->model->id);


        if ($this->model->load(Yii::$app->request->post())) {
          $this->model->invited = 0;
          $this->model->query = SondaggiInvitationsSearch::searchOrganizations(Yii::$app->request->post()['SondaggiInvitations'])->query->createCommand()->rawSql;
          $this->model->count = SondaggiInvitationsSearch::searchOrganizations(Yii::$app->request->post()['SondaggiInvitations'])->query->count();
          if ($this->model->validate()) {
            $this->model->save();
            if ($url) {
                return $this->redirect($url);
            } else {
                return $this->redirect(['update', 'id' => $this->model->id]);
            }
          } else {
            \Yii::debug($this->model->errors, 'sondaggi');
            return $this->render('update',
            [
                'model' => $this->model,
                'url' => ($url) ? $url : null,
            ]);
          }
        } else {
            return $this->render('update',
            [
                'model' => $this->model,
                'url' => ($url) ? $url : null,
            ]);
        }
    }

    /**
     * Deletes an existing SondaggiInvitations model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @param int $idSondaggio
     * @param null $url
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id, $idSondaggio, $url = null)
    {
        $this->model = $this->findModel($id);

        $this->model->delete();
        Yii::$app->getSession()->addFlash('success',
            AmosSondaggi::tHtml('amossondaggi', "#list_deleted"));

        if ($url) {
            return $this->redirect($url);
        } else {
            return $this->redirect(['index', 'idSondaggio' => $idSondaggio, 'url' => $url]);
        }
    }

    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setListViewsParams($idSondaggio = null)
    {
        $sondaggio = Sondaggi::findOne($idSondaggio);
        $canCreate = true;
        if ($canCreate) {
            $this->setCreateNewBtnParams();
        }
        $this->setUpLayout('list');
        Yii::$app->session->set(AmosSondaggi::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(AmosSondaggi::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnParams()
    {
        $get         = Yii::$app->request->get();
        $buttonLabel = AmosSondaggi::t('amossondaggi', 'Aggiungi pagina');
        $sondaggio   = Sondaggi::findOne(filter_input(INPUT_GET, 'idSondaggio'));

        $canCreate = true;


        $urlCreateNew = ['create'];
        if (isset($get['idSondaggio'])) {
            $urlCreateNew['idSondaggio'] = filter_input(INPUT_GET, 'idSondaggio');
        }
        if (isset($get['idPagina'])) {
            $urlCreateNew['idPagina'] = filter_input(INPUT_GET, 'idPagina');
        }
        if (isset($get['url'])) {
            $urlCreateNew['url'] = $get['url'];
        }
        if ($canCreate) {
            Yii::$app->view->params['createNewBtnParams'] = [
                'urlCreateNew' => $urlCreateNew,
                'createNewBtnLabel' => $buttonLabel
            ];
        }
        if (!empty($get['idSondaggio'])) {
            $backButton                                  = Html::a(AmosIcons::show('long-arrow-return',
                        ['class' => 'm-r-5']).AmosSondaggi::t('amossondaggi', "Torna ai sondaggi"),
                    ['/sondaggi/sondaggi/index'],
                    [
                    'class' => 'btn btn-secondary',
                    'title' => AmosSondaggi::t('amossondaggi', "Torna ai sondaggi")
            ]);
            Yii::$app->view->params['additionalButtons'] = [
                'htmlButtons' => [$backButton]
            ];
        }
    }

    /**
     * @param $model
     */
    public function setMenuSidebar($model)
    {
        \Yii::$app->getView()->params['showSidebarForm'] = true;
        \Yii::$app->getView()->params['bi-menu-sidebar'] = SondaggiUtility::getSidebarPages($model);
    }

    /**
     * @param $id
     */
    public function setScope($id)
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh)) {
            $moduleCwh->setCwhScopeInSession([
                'community' => $id,
                ],
                [
                'mm_name' => 'community_user_mm',
                'entity_id_field' => 'community_id',
                'entity_id' => $id
            ]);
        }
    }

    /**
     * @param $query_params
     * @return mixed
     */
    public function updateParams($query_params)
    {
        $name = 'SondaggiInvitations';
        $queryParamsToUpdate = $query_params[$name];
        $queryParamsToUpdate['field'] = null;
        $queryParamsToUpdate['includeExclude'] = null;
        $queryParamsToUpdate['value'] = null;
        $i = 1;
        foreach ($query_params[$name]['field'] as $key => $field) {
            $queryParamsToUpdate['field'][$i] = $field;
            $queryParamsToUpdate['includeExclude'][$i] = $query_params[$name]['includeExclude'];
            $queryParamsToUpdate['value'][$i][$i] = $query_params[$name]['value'][$key];
            $i++;
        }
        return $queryParamsToUpdate;
    }

    /**
     * @return string
     */
    public function actionRenderSearchAjax()
    {
        $post = \Yii::$app->request->post();

        parse_str(urldecode($post['data']), $query_params);
        $model = $this->model;
        $form = new ActiveForm();

        $queryParamsToUpdate = $this->updateParams($query_params);
        $model->attributes = $queryParamsToUpdate;
        $count = count($model->field);
        if (intval($post['plus']) == 1) {
            $count++;
        }

        return $this->renderAjax('_search_params', ['model' => $model, 'form' => $form, 'count' => $count]);
    }

    /**
     * @return array
     */
    public function actionGetValues()
    {
        $data = [];
        $parents = \Yii::$app->request->post('depdrop_parents');
        $query = \Yii::$app->request->get('q');
        $type = $parents[0];
        $data = SondaggiInvitationsSearch::getAttributesValues($type);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result = !empty($data) ? ['output' => $data] : null;
        return $result;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSearchInvited()
    {
      $post = \Yii::$app->request->post();
      parse_str(urldecode($post['data']), $query_params);
      $modelSearch = new SondaggiInvitationsSearch();
      $model = $this->model;
      $params = $query_params['SondaggiInvitations'];
      $model->attributes = $params;
      $query = $modelSearch->searchOrganizations($params)->query;
      $count = $query->count();
      $form = new ActiveForm();

      return $this->renderAjax('_results_search', [
          'count' => $count,
          'form' => $form,
          'model' => $model,
          'modelSearch' => $modelSearch,
      ]);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionActivate($id)
    {
      $this->model = $this->findModel($id);
      $this->model->active = true;
      if ($this->model->save())
        return;
      return yii\web\BadRequestHttpException;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDeactivate($id)
    {
      $this->model = $this->findModel($id);
      $this->model->active = false;
      if ($this->model->save())
        return;
      return yii\web\BadRequestHttpException;
    }

    /**
     * @return array
     */
    public function actionGroupList($q = null, $id = null)
    {
        $data = ProfiloGroups::find()->andWhere(['like', 'name', $q])->all();
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!empty($data)) {
          $out['results'] = [];
          foreach ($data as $result) {
            $out['results'][] = ['id' => $result->id, 'text' => $result->name];
          }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $out;
    }

    /**
     * @return array
     */
    public function actionTagList($q = null, $id = null)
    {
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $root = Tag::find()->andWhere(['codice' => Sondaggi::ROOT_TAG_CUSTOM_POLLS])->one();
      if ($root) {
        $data = Tag::find()->andWhere(['root' => $root->id])->andWhere(['!=', 'id', $root->id])->all();
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!empty($data)) {
          $out['results'] = [];
          foreach ($data as $result) {
            $out['results'][] = ['id' => $result->id, 'text' => $result->nome];
          }
        }
        return $out;
      }
      return [];
    }
}
