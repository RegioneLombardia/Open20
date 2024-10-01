<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\controllers 
 */
 
namespace open20\prodottiservizi\controllers;

use open20\amos\attachments\models\File;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\prodottiservizi\models\ProdottiDestinatari;
use open20\prodottiservizi\models\ProdottiFinalita;
use open20\prodottiservizi\models\ProdottiServizi;
use open20\prodottiservizi\models\ProdottiServiziAccordion;
use open20\prodottiservizi\models\ProdottiServiziDestinatariMm;
use open20\prodottiservizi\models\ProdottiServiziFinalitaMm;
use open20\prodottiservizi\models\ProdottiSoluzioni;
use open20\prodottiservizi\models\search\ProdottiDestinatariSearch;
use open20\prodottiservizi\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;
use yii\helpers\Url;

/**
 * Class ProdottiServiziController 
 * This is the class for controller "ProdottiServiziController".
 * @package open20\prodottiservizi\controllers
 */
class ProdottiServiziController extends \open20\prodottiservizi\controllers\base\ProdottiServiziController
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $get = Yii::$app->request->get();
       
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosdashboards', 'Prodotti e Servizi');
            $urlLinkAll   = '';

            $labelSigninOrSignup = Module::t('amosdashboards', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = Module::t(
                'amosdashboards',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = Module::t('amosdashboards', '#beforeActionCtaLogin');
            $titleSignin = Module::t(
                'amosdashboards',
                '#beforeActionCtaLoginTitle',
                ['platformName' => \Yii::$app->name]
            );
            /*
            $labelLink = $labelSigninOrSignup;
            $titleLink = $titleSigninOrSignup;
            $socialAuthModule = Yii::$app->getModule('socialauth');
            if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                $labelLink = $labelSignin;
                $titleLink = $titleSignin;
            }

            $ctaLoginRegister = Html::a(
                $labelLink,
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => $titleLink
                ]
            );
            $subTitleSection  = Html::tag(
                'p',
                Module::t(
                    'amosdashboards',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );*/
        } else {
            $titleSection = Module::t('amosdashboards', 'Prodotti e Servizi');

            $subTitleSection = Html::tag(
                'p',
                Module::t(
                    'prodottiservizi',
                    '#prodottiservizisubtitle'
                )
            );
        }

        $labelCreate = Module::t('amosdashboards', 'Nuovo');
        $titleCreate = Module::t('amosdashboards', 'Crea nuovo elemento');
        $labelManage = Module::t('amosdashboards', 'Gestisci');
        $titleManage = Module::t('amosdashboards', 'Gestisci la sidebar');
        $urlCreate   = '/prodotti-e-servizi/prodotti-servizi/create';
        $urlManage   = null;

        $this->view->params = [
            #'breadcrumbs' => $test,
            'isGuest' => \Yii::$app->user->isGuest,
            #'modelLabel' => 'Sidebar',
            'titleSection' => $titleSection,
            'hideCreate' => \Yii::$app->user->isGuest,
            #'subTitleSection' => $subTitleSection,
            #'urlLinkAll' => $urlLinkAll,
            #'labelLinkAll' => $labelLinkAll,
            #'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];

        // Lasciare qui questo if e return perché se no va in loop...
        if (!parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'view',
                            'crea-sportello',
                            'ajax-filter-prodotti',
                            'get-destinatari-by-ambito',
                            'amministra'
                        ],
                        'allow' => true,
                        'roles' => ['PRODOTTISERVIZI_ADMIN'],
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'ajax-filter-prodotti',
                            'get-destinatari-by-ambito'
                        ],
                        'allow' => true,
                        'roles' => ['?', 'PRODOTTISERVIZI_READER']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     *
     * @return array
     */
    public static function getManageLinks() {
        if(Yii::$app->user->can('PRODOTTISERVIZI_ADMIN')) {
            $links[] = [
                'title' => Module::t('prodottiservizi', 'Amministra i prodotti e servizi'),
                'label' => Module::t('prodottiservizi', 'Amministra Prodotti e servizi'),
                'url' => '/prodotti-e-servizi/prodotti-servizi/amministra'
            ];
            $links[] = [
                'title' => Module::t('prodottiservizi', 'Gestisci i destinatari'),
                'label' => Module::t('prodottiservizi', 'Gestisci Destinatari'),
                'url' => '/prodotti-e-servizi/prodotti-destinatari/index'
            ];
            $links[] = [
                'title' => Module::t('prodottiservizi', 'Gestisci le soluzioni'),
                'label' => Module::t('prodottiservizi', 'Gestisci Soluzioni'),
                'url' => '/prodotti-e-servizi/prodotti-soluzioni/index'
            ];
            $links[] = [
                'title' => Module::t('prodottiservizi', 'Gestisci le finalità'),
                'label' => Module::t('prodottiservizi', 'Gestisci Finalità'),
                'url' => '/prodotti-e-servizi/prodotti-finalita/index'
            ];
        }
        return $links;
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreaSportello($idProdotto=null){
        if(!Yii::$app->request->isAjax || is_null($idProdotto)) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $prodotto = ProdottiServizi::findOne($idProdotto);

            // Clone ProdottoServizio
            $clone = new ProdottiServizi();
            $clone->attributes = $prodotto->attributes;
            $clone->isNewRecord = true;
            $clone->id = null;
            $clone->id_product_root = $prodotto->id;
            $clone->save(false);

            // Clonaggio immagine
            $moduleAttachments = Yii::$app->getModule('attachments');
            if($moduleAttachments && $prodotto->getIcon()) {
                $moduleAttachments->attachFile($prodotto->getIcon()->getPath(), $clone, 'icon', false);
            }

            // Clone allegati
            $attachments = File::find()
                ->andWhere(['model' => ProdottiServizi::class])
                ->andWhere(['attribute' => 'attachments'])
                ->andWhere(['item_id' => $prodotto->id])
                ->all();

            foreach($attachments as $attach){
                $cloneFile = new File();
                $cloneFile->attributes = $attach->attributes;
                $cloneFile->isNewRecord = true;
                $cloneFile->id = null;
                $cloneFile->item_id = $clone->id;
                $cloneFile->save(false);
            }

            // Clone destinatari
            $prodottiServiziDestinatariMm = ProdottiServiziDestinatariMm::find()
                ->andWhere(['prodotto_id' => $prodotto->id])
                ->all();
            foreach($prodottiServiziDestinatariMm as $destinatario) {
                $cloneDestinatario = new ProdottiServiziDestinatariMm();
                $cloneDestinatario->prodotto_id = $clone->id;
                $cloneDestinatario->destinatario_id = $destinatario->destinatario_id;
                $cloneDestinatario->save();
            }

            // Clone finalità
            $prodottiServiziFinalitaMm = ProdottiServiziFinalitaMm::find()
                ->andWhere(['prodotto_id' => $prodotto->id])
                ->all();
            foreach($prodottiServiziFinalitaMm as $finalita) {
                $cloneFinalita = new ProdottiServiziFinalitaMm();
                $cloneFinalita->prodotto_id = $clone->id;
                $cloneFinalita->finalita_id = $finalita->finalita_id;
                $cloneFinalita->save();
            }

            // Clone Accordion dinamici
            $prodottiServiziAccordion = ProdottiServiziAccordion::find()
                ->andWhere(['prodotto_id' => $prodotto->id])
                ->all();
            foreach($prodottiServiziAccordion as $accordion) {
                $cloneAccordion = new ProdottiServiziAccordion();
                $cloneAccordion->attributes = $accordion->attributes;
                $cloneAccordion->isNewRecord = true;
                $cloneAccordion->id = null;
                $cloneAccordion->prodotto_id = $clone->id;
                $cloneAccordion->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
        return true;
    }

    /**
     * @return false|string
     */
    public function actionAjaxFilterProdotti() {
        if(!\Yii::$app->request->isAjax) return false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $get = \Yii::$app->request->get();

        // Rimuovo il filtro sulla soluzione per applicarlo dopo, mentre si cicla il dataprovider
        $solutionFilter = null;
        if(isset($get['ProdottiServiziSearch']['solution'][0])) {
            $solutionFilter = $get['ProdottiServiziSearch']['solution'][0];
            unset($get['ProdottiServiziSearch']['solution']);
        }

        // Ricerca risultati
        $dataProvider = $this->modelSearch->search($get, 'and');

        // Pre-pruning
        $models = $dataProvider->getModels();
        $dataCategoryDestinatari = 'dest:::';
        $dataCategorySolution = 'solu:::';
        $dataCategoryFinalita = 'fina:::';
        $dataDestinatari = [];
        $dataSolution = [];
        $dataFinalita = [];
        foreach($models as $key => $model){
            $value = 'solu-' . $model->solution;
            (!in_array($value, $dataSolution)) ? $dataSolution[] = $value : true;
            // Filtro per soluzione
            if(!is_null($solutionFilter) && $model->solution != $solutionFilter) {
                unset($models[$key]);
            }
            else {
                $finalita = $model->finalitaMm;
                foreach($finalita as $fin){
                    $value = 'fina-' . $fin->finalita_id;
                    (!in_array($value, $dataFinalita)) ? $dataFinalita[] = $value : true;
                }
                $destinatari = $model->destinatariMm;
                foreach($destinatari as $dest){
                    $value = 'dest-' . $dest->destinatario_id;
                    (!in_array($value, $dataDestinatari)) ? $dataDestinatari[] = $value : true;
                }
            }
        }
        $dataProvider->setModels($models);

        $dataDestinatari = implode(',', $dataDestinatari);
        $dataSolution = implode(',', $dataSolution);
        $dataFinalita = implode(',', $dataFinalita);

        if(strlen($dataSolution) > 0){
            $dataSolution .= ':::';
        }
        else{
            $dataSolution = ':::';
        }
        if(strlen($dataFinalita) > 0){
            $dataFinalita .= ':::';
        }
        else{
            $dataFinalita = ':::';
        }

        return $this->renderPartial('fullsize/parts/_dataproviderProdotti',[
            'dataProvider' => $dataProvider,
            'currentView' => $this->getCurrentView('grid')
        ]) . '|||' . $dataCategorySolution . $dataSolution . $dataCategoryFinalita . $dataFinalita . $dataCategoryDestinatari . $dataDestinatari;
    }

    /**
     * @return false|string
     */
    public function actionGetDestinatariByAmbito() {
        if(!\Yii::$app->request->isAjax) return false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $modelSearch = new ProdottiDestinatariSearch();
        $dataProvider = $modelSearch->search(\Yii::$app->request->post());

        return $this->renderPartial('fullsize/parts/_destinatariOptions', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionAmministra() {
        Url::remember();
        $this->setUpLayout('list');
        $this->view->params['labelLinkAll'] = Module::t('prodottiservizi', 'Tutti i prodotti e servizi');
        $this->view->params['urlLinkAll'] = '/prodotti-e-servizi/prodotti-servizi/index';
        $this->view->params['titleSection'] = 'Amministra Prodotti e Servizi';
        $this->view->params['containerFullWidth'] = true;
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . \open20\amos\core\helpers\Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ]
        ]);
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $currentView = $this->getAvailableView('grid');
        return $this->render('amministra', [
            'dataProvider' => $this->dataProvider,
            'currentView' => $currentView,
            'model' => $this->modelSearch,
            'soluzioni' => ArrayHelper::map(ProdottiSoluzioni::find()->orderBy('name ASC')->all(), 'id', 'name'),
            'finalita' => ArrayHelper::map(ProdottiFinalita::find()->orderBy('name ASC')->all(), 'id', 'name'),
            'destinatari' => ArrayHelper::map(ProdottiDestinatari::find()->orderBy('name ASC')->all(), 'id', 'name')
        ]);
    }
}
