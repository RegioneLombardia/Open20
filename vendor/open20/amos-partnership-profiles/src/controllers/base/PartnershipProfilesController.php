<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\controllers\base
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\controllers\base;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use open20\amos\partnershipprofiles\models\PartnershipProfilesCountriesMm;
use open20\amos\partnershipprofiles\models\PartnershipProfilesTypesMm;
use open20\amos\partnershipprofiles\Module;
use open20\amos\partnershipprofiles\utility\PartnershipProfilesUtility;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard;
use Yii;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * Class PartnershipProfilesController
 * PartnershipProfilesController implements the CRUD actions for PartnershipProfiles model.
 *
 * @property \open20\amos\partnershipprofiles\models\PartnershipProfiles $model
 * @property \open20\amos\partnershipprofiles\models\search\PartnershipProfilesSearch $modelSearch
 *
 * @package open20\amos\partnershipprofiles\controllers\base
 */
class PartnershipProfilesController extends CrudController
{

    /**
     * Trait used for initialize the news dashboard
     */
    use TabDashboardControllerTrait;
    /**
     * @var Module $partnerProfModule
     */
    public $partnerProfModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->partnerProfModule = Yii::$app->getModule(Module::getModuleName());

        $this->initDashboardTrait();

        $this->setModelObj($this->partnerProfModule->createModel('PartnershipProfiles'));
        $this->setModelSearch($this->partnerProfModule->createModel('PartnershipProfilesSearch'));

        $this->viewList = [
            'name' => 'list',
            'label' => AmosIcons::show('view-list').Html::tag('p', Module::tHtml('amospartnershipprofiles', 'List')),
            'url' => '?currentView=list'
        ];

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt').Html::tag('p', Module::tHtml('amospartnershipprofiles', 'Table')),
            'url' => '?currentView=grid'
        ];

        $defaultViews = [
            'list' => $this->viewList,
            'grid' => $this->viewGrid,
        ];

        $availableViews = [];

        foreach ($this->partnerProfModule->defaultListViewsPartnerProf as $view) {
            if (isset($defaultViews[$view])) {
                $availableViews[$view] = $defaultViews[$view];
            }
        }

        $this->setAvailableViews($availableViews);

        parent::init();


        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->view->pluginIcon = 'ic ic-propostecollaborazione';
        }

        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle
     */
    public function setTitleAndBreadcrumbs($pageTitle)
    {
        Yii::$app->view->title                 = $pageTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $pageTitle]
        ];
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     * @param bool $hideBtn
     */
    protected function setCreateNewBtnLabel($hideBtn = false)
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => Module::t('amospartnershipprofiles', 'Add new partnership profile')
        ];
        if ($hideBtn) {
            $this->hideCreateNewBtn();
        }
    }

    /**
     * Method useful to hide the create new button.
     */
    protected function hideCreateNewBtn()
    {
        Yii::$app->view->params['createNewBtnParams']['layout'] = '';
    }

    /**
     * Set the begin create new button session key link and datetime.
     */
    protected function setSessionBeginCreateLink()
    {
        Yii::$app->session->set(Module::beginCreateNewSessionKeyPartnershipProfiles(), Url::previous());
        Yii::$app->session->set(Module::beginCreateNewSessionKeyPartnershipProfilesDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * This method is useful to set all common params for all list views.
     * @param bool $setCurrentDashboard
     * @param bool $hideCreateNewBtn
     */
    protected function setListViewsParams($setCurrentDashboard = true, $hideCreateNewBtn = false, $child_of = null)
    {
        $this->child_of = (($child_of === null) ? WidgetIconPartnershipProfilesDashboard::className() : $child_of);
        $this->setCreateNewBtnLabel($hideCreateNewBtn);
        $this->setUpLayout('list');
        if ($setCurrentDashboard) {
            $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        }
        $this->setSessionBeginCreateLink();
    }

    /**
     * This method returns the close url for close button in action view.
     * @return string
     */
    public function getViewCloseUrl()
    {
        return Yii::$app->session->get(Module::beginCreateNewSessionKeyPartnershipProfiles());
    }

    /**
     * This method returns the close url for close button in form.
     * @return string
     */
    public function getFormCloseUrl()
    {
        $redirectUrl = Yii::$app->session->get(Module::beginCreateNewSessionKeyPartnershipProfiles());
        $url =(strlen($redirectUrl) > 0 ? $redirectUrl : \Yii::$app->session->get('previousUrl'));
        if(empty($url)){
            return \Yii::$app->request->referrer;
        }
        return $url;
    }

    /**
     * This method find all logged user own interests partnership profiles.
     * The method returns an array of PartnershipProfiles objects or an array of ids.
     * @param bool $onlyIds
     * @return PartnershipProfiles[]
     */
    public function getOwnInterestPartnershipProfiles($onlyIds = false)
    {
        return PartnershipProfilesUtility::getOwnInterestPartnershipProfiles($onlyIds);
    }

    /**
     * Save all values selected by user in a multi select field.
     * @param array $attrMmPost
     * @param string $modelClassName
     * @param string $thisModelIdField
     * @param string $otherIdField
     * @return bool
     */
    protected function saveMmsFields($attrMmPost, $modelClassName, $thisModelIdField, $otherIdField)
    {
        $allOk = true;
        if (!empty($attrMmPost) && is_array($attrMmPost)) {

            // Remove all old references
            $attribute = $modelClassName::find()
                ->andWhere([
                    $thisModelIdField => $this->model->id,
                ])
                ->all();

            if ($attribute) {
                foreach ($attribute as $att) {
                    $att->delete();
                }
            }

            foreach ($attrMmPost as $attrId) {
                /** @var \open20\amos\core\record\Record $attrMmModel */
                $attrMmModel                      = new $modelClassName();
                $attrMmModel->{$thisModelIdField} = $this->model->id;
                $attrMmModel->{$otherIdField}     = $attrId;
                $ok                               = $attrMmModel->save(false);
                if (!$ok) {
                    $allOk = false;
                }
            }
        }

        return $allOk;
    }

    /**
     * Save all partnership profile types selected by user.
     * @param array $attrPost
     * @return bool
     */
    protected function savePartnershipProfileTypes($attrPost)
    {
        if (!is_array($attrPost)) {
            $attrPost = [$attrPost];
        }
        return $this->saveMmsFields(
                $attrPost, PartnershipProfilesTypesMm::className(), 'partnership_profile_id',
                'partnership_profiles_type_id'
        );
    }

    /**
     * Save all partnership profile countries selected by user.
     * @param array $attrPost
     * @return bool
     */
    protected function savePartnershipProfileCountries($attrPost)
    {
        if (!is_array($attrPost)) {
            $attrPost = [$attrPost];
        }
        return $this->saveMmsFields(
                $attrPost, PartnershipProfilesCountriesMm::className(), 'partnership_profile_id', 'country_id'
        );
    }

    /**
     * Set the partnership facilitator with the user profile facilitator.
     * @return bool
     */
    protected function setDefaultFacilitator()
    {
        $loggedUserProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if (is_null($loggedUserProfile)) {
            return false;
        }
        if (!$this->model->partnership_profile_facilitator_id) {
            $loggedUserFacilitatorUserProfile = $loggedUserProfile->facilitatore;
            if (!is_null($loggedUserFacilitatorUserProfile) && $loggedUserFacilitatorUserProfile->isActive()) {
                $this->model->partnership_profile_facilitator_id = $loggedUserFacilitatorUserProfile->user_id;
            } else {
                $defaultFacilitator = $loggedUserProfile->getDefaultFacilitator();
                if (!is_null($defaultFacilitator)) {
                    $this->model->partnership_profile_facilitator_id = $defaultFacilitator->user_id;
                } else {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Lists all PartnershipProfiles models.
     * @param string|null $layout
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = null)
    {
        Url::remember();

        Yii::$app->view->params['textHelp']['filename'] = 'partnership_dashboard_description';
        $modelSearch                                    = $this->modelSearch;
        if (\Yii::$app->getModule('notify')) {
            $modelSearch->setNotifier(\Yii::$app->getModule('notify'));
        }
        $this->setDataProvider($this->modelSearch->searchAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(Module::t('amospartnershipprofiles', 'All partnership profiles'));
        $this->setListViewsParams();
        if (!is_null($layout)) {
            $this->layout = $layout;
        }

        return parent::actionIndex();
    }

    /**
     * Displays a single PartnershipProfiles model.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionView($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        if (!PartnershipProfilesUtility::canView($this->model)) {
            throw new ForbiddenHttpException();
        }

        $this->setSessionBeginCreateLink();
        $this->model->setScenario(PartnershipProfiles::SCENARIO_VIEW);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save(false)) {
            if (Yii::$app->user->can('PARTNERSHIPPROFILES_READ', $this->model)) {
                return $this->redirect(['view', 'id' => $this->model->id]);
            } else {
                return $this->redirect(Yii::$app->session->get(Module::beginCreateNewSessionKeyPartnershipProfiles()));
            }
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * Creates a new PartnershipProfiles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');

        $this->model   = $this->partnerProfModule->createModel('PartnershipProfiles');
        $okFacilitator = $this->setDefaultFacilitator();
        $this->model->loadOtherCategories();

        Yii::$app->view->params['textHelp']['filename'] = 'expression_of_interest_description';
        $useUpdate                                      = true;
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($okFacilitator) {
                $validateOnSave = true;
                if ($this->model->status == PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_TOVALIDATE) {
                    $this->model->status = PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT;
                    $this->model->save();
                    $this->model->status = PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_TOVALIDATE;
                    $validateOnSave      = false;
                    $useUpdate           = false;
                }

                if ($this->model->status == PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED) {
                    $this->model->status = PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT;
                    $this->model->save();
                    $this->model->status = PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_TOVALIDATE;
                    $this->model->save();
                    $this->model->status = PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED;
                    $validateOnSave      = false;
                    $useUpdate           = false;
                }
                $this->model->saveOtherCategories();

                $attrPartnershipProfilesTypesMmPost = [];
                if (!empty(\Yii::$app->request->post('PartnershipProfiles')['attrPartnershipProfilesTypesMm'])) {
                    $attrPartnershipProfilesTypesMmPost = \Yii::$app->request->post('PartnershipProfiles')['attrPartnershipProfilesTypesMm'];
                }
                $attrPartnershipProfilesCountriesMmPost = [];
                if (!empty(\Yii::$app->request->post('PartnershipProfiles')['attrPartnershipProfilesCountriesMm'])) {
                    $attrPartnershipProfilesCountriesMmPost = \Yii::$app->request->post('PartnershipProfiles')['attrPartnershipProfilesCountriesMm'];
                }
                if ($this->model->save($validateOnSave)) {
                    $okPartnershipProfileType      = $this->savePartnershipProfileTypes($attrPartnershipProfilesTypesMmPost);
                    $okPartnershipProfileCountries = $this->savePartnershipProfileCountries($attrPartnershipProfilesCountriesMmPost);
                    if ($okPartnershipProfileType && $okPartnershipProfileCountries) {
                        Yii::$app->getSession()->addFlash('success',
                            Module::tHtml('amospartnershipprofiles', 'Element successfully created.'));
                    } else if (!$okPartnershipProfileType && $okPartnershipProfileCountries) {
                        Yii::$app->getSession()->addFlash('danger',
                            Module::tHtml('amospartnershipprofiles', '#error_saving_partnership_profile_type'));
                    } else if ($okPartnershipProfileType && !$okPartnershipProfileCountries) {
                        Yii::$app->getSession()->addFlash('danger',
                            Module::tHtml('amospartnershipprofiles', '#error_saving_partnership_profile_countries'));
                    } else if (!$okPartnershipProfileType && !$okPartnershipProfileCountries) {
                        Yii::$app->getSession()->addFlash('danger',
                            Module::tHtml('amospartnershipprofiles',
                                '#error_saving_partnership_profile_countries_and_types'));
                    }
                    if ($useUpdate) {
                        return $this->redirect(['update', 'id' => $this->model->id]);
                    } else {
                        return $this->redirect(['view', 'id' => $this->model->id]);
                    }
                } else {
                    Yii::$app->getSession()->addFlash('danger',
                        Module::tHtml('amospartnershipprofiles', 'Element not created, check the data entered.'));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    Module::tHtml('amospartnershipprofiles', 'Error with partnership profile facilitator'));
            }
        }

        return $this->render('create',
                [
                'model' => $this->model,
                'fid' => null,
                'dataField' => null,
                'dataEntity' => null
        ]);
    }

    /**
     * Updates an existing PartnershipProfiles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        Yii::$app->view->params['textHelp']['filename']  = 'expression_of_interest_description';
        $this->model                                     = $this->findModel($id);
        $this->setDefaultFacilitator();
        $this->model->attrPartnershipProfilesTypesMm     = $this->model->partnershipProfilesTypes;
        $this->model->attrPartnershipProfilesCountriesMm = $this->model->partnershipProfileCountries;
        $this->model->loadOtherCategories();


        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $attrPartnershipProfilesTypesMmPost = [];
            if (!empty(\Yii::$app->request->post('PartnershipProfiles')['attrPartnershipProfilesTypesMm'])) {
                $attrPartnershipProfilesTypesMmPost = \Yii::$app->request->post('PartnershipProfiles')['attrPartnershipProfilesTypesMm'];
            }
            $attrPartnershipProfilesCountriesMmPost = [];

            if (!empty(\Yii::$app->request->post('PartnershipProfiles')['attrPartnershipProfilesCountriesMm'])) {
                $attrPartnershipProfilesCountriesMmPost = \Yii::$app->request->post('PartnershipProfiles')['attrPartnershipProfilesCountriesMm'];
            }

            if ($this->model->save()) {
                $this->model->saveOtherCategories();
                $okPartnershipProfileType      = $this->savePartnershipProfileTypes($attrPartnershipProfilesTypesMmPost);
                $okPartnershipProfileCountries = $this->savePartnershipProfileCountries($attrPartnershipProfilesCountriesMmPost);
                if ($okPartnershipProfileType && $okPartnershipProfileCountries) {
                    Yii::$app->getSession()->addFlash('success',
                        Module::tHtml('amospartnershipprofiles', 'Element successfully updated.'));
                } else if (!$okPartnershipProfileType && $okPartnershipProfileCountries) {
                    Yii::$app->getSession()->addFlash('danger',
                        Module::tHtml('amospartnershipprofiles', '#error_saving_partnership_profile_type'));
                } else if ($okPartnershipProfileType && !$okPartnershipProfileCountries) {
                    Yii::$app->getSession()->addFlash('danger',
                        Module::tHtml('amospartnershipprofiles', '#error_saving_partnership_profile_countries'));
                } else if (!$okPartnershipProfileType && !$okPartnershipProfileCountries) {
                    Yii::$app->getSession()->addFlash('danger',
                        Module::tHtml('amospartnershipprofiles', '#error_saving_partnership_profile_countries_and_types'));
                }
                if (Yii::$app->user->can('PARTNERSHIPPROFILES_UPDATE', ['model' => $this->model])) {
                    return $this->redirect(['update', 'id' => $this->model->id]);
                } else {
                    return $this->redirect(Yii::$app->session->get(Module::beginCreateNewSessionKeyPartnershipProfiles()));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    Module::tHtml('amospartnershipprofiles', 'Element not updated, check the data entered.'));
            }
        }

        return $this->render(
                'update',
                [
                'model' => $this->model,
                'fid' => null,
                'dataField' => null,
                'dataEntity' => null
                ]
        );
    }

    /**
     * Deletes an existing PartnershipProfiles model.
     * If deletion is successful, the browser will be redirected to the previous list page.
     * @param int $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $expressionsOfInterest = $this->model->expressionsOfInterest;
            if (empty($expressionsOfInterest)) {
                $this->model->delete();
                if (!$this->model->hasErrors()) {
                    Yii::$app->getSession()->addFlash('success',
                        Module::t('amospartnershipprofiles', 'Partnership profile deleted successfully.'));
                } else {
                    Yii::$app->getSession()->addFlash('danger',
                        Module::t('amospartnershipprofiles',
                            'You are not authorised to delete this partnership profile.'));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    Module::t('amospartnershipprofiles',
                        'You can not delete this partnership profile because there is at least one expression of interest.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger',
                Module::tHtml('amospartnershipprofiles', 'Partnership profile not found.'));
        }
        return $this->redirect(Yii::$app->session->get(Module::beginCreateNewSessionKeyPartnershipProfiles()));
    }
}