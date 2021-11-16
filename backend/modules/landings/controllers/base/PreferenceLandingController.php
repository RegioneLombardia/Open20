<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\landings\controllers\base
 */

namespace backend\modules\landings\controllers\base;

use open20\amos\sondaggi\models\Sondaggi;
use Yii;
use backend\modules\landings\models\PreferenceLanding;
use backend\modules\landings\models\search\PreferenceLandingSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;
use open20\amos\admin\models\UserProfile;
use yii\db\Transaction;


/**
 * Class PreferenceLandingController
 * PreferenceLandingController implements the CRUD actions for PreferenceLanding model.
 *
 * @property \backend\modules\landings\models\PreferenceLanding $model
 * @property \backend\modules\landings\models\search\PreferenceLandingSearch $modelSearch
 *
 * @package backend\modules\landings\controllers\base
 */
class PreferenceLandingController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new PreferenceLanding());
        $this->setModelSearch(new PreferenceLandingSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
            /*'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),         
                'url' => '?currentView=list'
            ],
            'icon' => [
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
        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar', []);    }

    /**
     * Lists all PreferenceLanding models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        \Yii::$app->view->params['enableLayoutList'] = true;
        \Yii::$app->view->params['enableChangeView'] = true;
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));

        // button create landing page
        \Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => 'Nuova Landing Page',//la label
            'urlCreateNew' => '/landings/preference-landing/create', // eventuale url
        ];

        return $this->render(
            'index',
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
     * Displays a single PreferenceLanding model.
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
     * Creates a new PreferenceLanding model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new PreferenceLanding();

        $utility = new \amos\cmsbridge\utility\CmsUtility();
        $templates = $utility->getCmsTemplates();
        $arrayTemplates = [];
        $htmlTemplate = [];
        foreach ($templates as $template) {
            $arrayTemplates [$template['id']] = $template['title'];
            $htmlTemplate[$template['id']] = 'gradient-blu';
        }

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        // tutti i sondaggi in stato validato
        $sondaggiChoices = $this->getAllSondaggiMapByStatus($this->model,Sondaggi::WORKFLOW_STATUS_VALIDATO);

        return $this->render('create', [
            'model' => $this->model,
            'arrayTemplates' => $arrayTemplates,
            'htmlTemplate' => $htmlTemplate,
            'sondaggiChoices' => $sondaggiChoices,
        ]);
    }

    /**
     * @param PreferenceLanding $landing
     * @param $status
     * @param string $from
     * @param string $to
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private function getAllSondaggiMapByStatus($landing, $status, string $from = 'id', string $to = 'titleStartDateLabel'): array
    {
        $toret = [];
        if (!empty(\Yii::$app->getModule('sondaggi'))) {
            $query = PreferenceLanding::find()
                ->select(new Expression('DISTINCT(sondaggi_id)'))
                ->andWhere(['IS NOT', 'sondaggi_id', null])
            ;

            if (!empty($landing->id)){
                $query->andWhere(['!=', 'id', $landing->id]);
            }

            $sondaggiUsati = $query->asArray()->all();

            // trovo gli id di sondaggio che non posso essere elencati!
            // perchè già usati in un'altra landing
            $ids = [];
            if (!empty($sondaggiUsati)) {
                foreach ($sondaggiUsati as $val) {
                    $ids[] = intval($val['sondaggi_id']);
                }
            }

            /** @var ActiveQuery $querySondaggi */
            $querySondaggi = Sondaggi::find();
            $querySondaggi
                ->andWhere(['NOT IN', 'id' , $ids])
                ->andWhere(['sondaggi.status' => $status]);

            $toret = ArrayHelper::map($querySondaggi->all(), $from, $to);
        }
        return $toret;
    }


    /**
     * Creates a new PreferenceLanding model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new PreferenceLanding();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                //Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
            //Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->renderAjax('_formAjax', [
            'model' => $this->model,
            'fid' => $fid,
            'dataField' => $dataField
        ]);
    }

    /**
     * Updates an existing PreferenceLanding model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);

        $utility = new \amos\cmsbridge\utility\CmsUtility();
        $templates = $utility->getCmsTemplates();
        $arrayTemplates = [];
        $htmlTemplate = [];
        foreach ($templates as $template) {
            $arrayTemplates [$template['id']] = $template['title'];
            $htmlTemplate[$template['id']] = 'gradient-blu';
        }

        $dataProviderProtagonists = new ActiveDataProvider([
            'query' => $this->model->getPreferenceLandingProtagonists()
        ]);

        $dataProviderNews = new ActiveDataProvider([
            'query' => $this->model->getNews()
        ]);

        $dataProviderLinks = new ActiveDataProvider([
            'query' => $this->model->getLinks()->orderBy('sort_value')
        ]);

        $dataProviderDocumenti = new ActiveDataProvider([
            'query' => $this->model->getDocumenti()
                ->innerJoin('preference_landing_documenti', 'documenti.id = preference_landing_documenti.documenti_id')
                ->orderBy('preference_landing_documenti.sort_value')
        ]);

        $slider = $this->model->imageSlider;
        if (empty($slider)) {
            $slider = new \amos\sitemanagement\models\SiteManagementSlider();
            $slider->section_id = null;
            $slider->title =  $this->model->title;
            $slider->save(false);
            $this->model->image_slider_id = $slider->id;
            $this->model->save(false);
        }

        $sliderVideo = $this->model->videoSlider;
        if (empty($sliderVideo)) {
            $sliderVideo = new \amos\sitemanagement\models\SiteManagementSlider();
            $sliderVideo->section_id = null;
            $sliderVideo->title =  $this->model->title;
            $sliderVideo->save(false);
            $this->model->video_slider_id = $sliderVideo->id;
            $this->model->save(false);
        }

        $dataProviderSliderElemImage = new ActiveDataProvider(['query' => $slider->getSliderElems()->orderBy('order ASC')]);
        $dataProviderSliderElemVideo = new ActiveDataProvider(['query' => $sliderVideo->getSliderElems()->orderBy('order ASC')]);


        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if(\Yii::$app->request->post('publish-action') == 1){
                $this->model->status = PreferenceLanding::PREFERENCE_LANDING_STATUS_PUBLISHED;
            }else if(\Yii::$app->request->post('unpublish-action') == 1){
                $this->model->status = PreferenceLanding::PREFERENCE_LANDING_STATUS_DRAFT;
            }

            if ($this->model->save()) {

                // sistema salva dati
                if (\Yii::$app->request->post('save_and_redirect_to')) {
                    return $this->redirect(\Yii::$app->request->post('save_and_redirect_to'));
                }

                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
            }
        }

        // tutti i sondaggi in stato validato
        $sondaggiChoices = $this->getAllSondaggiMapByStatus($this->model,Sondaggi::WORKFLOW_STATUS_VALIDATO);

        return $this->render('update', [
            'model' => $this->model,
            'arrayTemplates' => $arrayTemplates,
            'htmlTemplate' => $htmlTemplate,
            'dataProviderSliderElemImage' => $dataProviderSliderElemImage,
            'dataProviderSliderElemVideo' => $dataProviderSliderElemVideo,
            'dataProviderProtagonists' => $dataProviderProtagonists,
            'dataProviderNews' => $dataProviderNews,
            'dataProviderDocumenti' => $dataProviderDocumenti,
            'dataProviderLinks' => $dataProviderLinks,
            'sondaggiChoices' => $sondaggiChoices,
        ]);
    }

    /**
     * Deletes an existing PreferenceLanding model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);


        $utility = new \amos\cmsbridge\utility\CmsUtility();
        $page = new \amos\cmsbridge\models\PostCmsCreatePage();

        // 
        $page->nav_id = $this->model->luya_page_id;

        // CONFIGURATION LANDING PAGE
        $page->nav_item_type = 1;
        $page->parent_nav_id = 0;
        $page->is_draft = 0;
        $page->nav_container_id = 1;
        $page->lang_id = 1;
        $page->use_draft = 1;
        $page->layout_id = 0;
        $page->from_draft_id = $this->model->luya_template_id;
        $page->title = $this->model->title; // Titolo pagina
        $page->alias = $this->model->url; // alias pagina

        // DYNAMIC FIELD LANDING PAGE
        $page->event_data->event_id = $this->model->id; // id dell'evento
        $page->event_data->title = $this->model->title;
        //        $page->event_data->event_date = $dateString;
        $page->event_data->presentation = $this->model->description;
        $page->event_data->program = $this->model->schedule;
        $page->event_data->description = $this->model->description;

        $page->cms_user_id = $utility->loginCms();
        $resultDelete = $utility->deleteCmsPage($page);


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
