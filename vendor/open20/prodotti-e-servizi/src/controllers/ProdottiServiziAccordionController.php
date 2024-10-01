<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\prodottiservizi\controllers
 */

namespace open20\prodottiservizi\controllers;

use open20\prodottiservizi\models\ProdottiServiziAccordion;
use Yii;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\prodottiservizi\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Response;

/**
 * Class ProdottiServiziAccordionController
 * This is the class for controller "ProdottiServiziAccordionController".
 * @package open20\prodottiservizi\controllers
 */
class ProdottiServiziAccordionController extends \open20\prodottiservizi\controllers\base\ProdottiServiziAccordionController
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $get = \Yii::$app->request->get();

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
            $titleSection = Module::t('amosdashboards', 'Accordion dinamici');

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
        $titleManage = Module::t('amosdashboards', 'Gestisci gli accordion');
        $urlCreate   = '/prodotti-e-servizi/prodotti-servizi-accordion/create';
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
                            'create-ajax',
                            'update-ajax',
                            'delete-ajax',
                            'get-accordion-ajax',
                            'swap-position',
                            'get-create-form-ajax'
                        ],
                        'allow' => true,
                        'roles' => ['PRODOTTISERVIZI_ADMIN'],
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
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
     * @param $id
     * @return false|string
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDeleteAjax($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(!\Yii::$app->request->isAjax) return false;

        $this->model = $this->findModel($id);
        if($this->model) {
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                $params['ProdottiServiziAccordionSearch']['prodotto_id'] = $this->model->prodotto_id;
                $dataProviderAccordion = $this->getModelSearch()->search($params);

                return $this->renderAjax('fullsize/parts/_dataProviderViewAccordion', [
                    'dataProviderAccordion' => $dataProviderAccordion,
                    'currentViewAccordion' => [
                        'name' => 'grid',
                        'label' => AmosIcons::show('view-list-alt') . \open20\amos\core\helpers\Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                        'url' => '?currentView=grid'
                    ]
                ]);
            }
        }
        return false;
    }

    /**
     * @param $id
     * @return false|string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionGetAccordionAjax($id)
    {
        /** @var ProdottiServiziAccordion $model */
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(!\Yii::$app->request->isAjax) return false;

        $model = $this->findModel($id);
        if($model) {
            //Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('prodottiservizi', 'test'));
            return $this->renderAjax('fullsize/parts/_accordionUpdateModal', [
                'model' => $model,
                'idProdotto' => $model->prodotto_id
            ]);
        }
        return false;
    }

    /**
     * @return false|string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdateAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(!\Yii::$app->request->isAjax) return false;

        $this->model = $this->findModel(Yii::$app->request->get('ProdottiServiziAccordion')['id']);

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->get())) {
            //$this->model->description = strip_tags($this->model->description);
            if($this->model->validate()) {
                if ($this->model->save()) {
                    if($this->model->prodotto_id == '') $this->model->prodotto_id = null;
                    $params['ProdottiServiziAccordionSearch']['prodotto_id'] = $this->model->prodotto_id;
                    $dataProviderAccordion = $this->getModelSearch()->search($params);
                    return $this->renderAjax('fullsize/parts/_dataProviderViewAccordion', [
                        'dataProviderAccordion' => $dataProviderAccordion,
                        'currentViewAccordion' => [
                            'name' => 'grid',
                            'label' => AmosIcons::show('view-list-alt') . \open20\amos\core\helpers\Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                            'url' => '?currentView=grid'
                        ]
                    ]);
                }
            }
        }
        return false;
    }

    public function actionSwapPosition()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(!\Yii::$app->request->isAjax) return false;

        /**
         * idClicked: id dell'item che si vuole spostare
         */
        $idClicked = \Yii::$app->request->get('idClicked');
        $operationType = \Yii::$app->request->get('operationType');
        $idProdotto = (\Yii::$app->request->get('idProdotto') == '') ? null : \Yii::$app->request->get('idProdotto');

        $modelClicked = ProdottiServiziAccordion::findOne($idClicked);
        if($modelClicked->prodotto_id == '') $modelClicked->prodotto_id = null;
        $posNeighbor = ($operationType == ProdottiServiziAccordion::UP_MOVE_OPERATION) ?
            ($modelClicked->getPreviousAccordion($idProdotto)) : ($modelClicked->getFollowingAccordion($idProdotto));

        $modelNeighbor = ProdottiServiziAccordion::find()
            ->andWhere(['position' => $posNeighbor])
            ->andWhere(['prodotto_id' => $modelClicked->prodotto_id])
            ->one();

        if($modelClicked && $modelNeighbor){
            // Scambio la posizione
            $tmp = $modelClicked->position;
            $modelClicked->position = $modelNeighbor->position;
            $modelNeighbor->position = $tmp;
            $modelClicked->save();
            $modelNeighbor->save();

            $params['ProdottiServiziAccordionSearch']['prodotto_id'] = $idProdotto;
            $dataProviderAccordion = $this->getModelSearch()->search($params);
            return $this->renderAjax('fullsize/parts/_dataProviderViewAccordion', [
                'dataProviderAccordion' => $dataProviderAccordion,
                'currentViewAccordion' => [
                    'name' => 'grid',
                    'label' => AmosIcons::show('view-list-alt') . \open20\amos\core\helpers\Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                    'url' => '?currentView=grid'
                ]
            ]);
        }
        return false;
    }

    public function actionGetCreateFormAjax($idProdotto)
    {
        /** @var ProdottiServiziAccordion $model */
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(!\Yii::$app->request->isAjax) return false;

        $model = new ProdottiServiziAccordion();
        return $this->renderAjax('fullsize/parts/_accordionModal', [
            'model' => $model,
            'idProdotto' => $idProdotto
        ]);
    }
}
