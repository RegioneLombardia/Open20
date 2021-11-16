<?php

namespace app\modules\backendobjects\frontend\controllers;

use luya\cms\frontend\base\Controller;
use Yii;

/**
 * Class DefaultController
 * @package app\modules\backendobjects\frontend\controllers
 */
class DefaultController extends Controller
{
    public $backendModule;
    public $modelSearchClass;
    public $detailPage;
    private $queryVars = [
        'amp',
        'path'
    ];

    /**
     * @return boolean
     */
    private function isAmp()
    {
        $is = false;

        foreach ($this->queryVars as $var) {
            $value = \Yii::$app->request->getQueryParam($var);
            if (!empty($value)) {
                if ($value == 'amp') {
                    $is = true;
                }
                break;
            }
        }
        return $is;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @param array $parms
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(array $parms)
    {
        $this->detailPage = $parms["detailPage"];
        $backendModule    = $parms["backendModule"];

        if (class_exists($backendModule) && in_array('open20\amos\core\interfaces\CmsModuleInterface',
                class_implements($backendModule))) {
            $modelSearchClass = $backendModule::getModelSearchClassName();

            if (class_exists($modelSearchClass) && in_array('open20\amos\core\interfaces\CmsModelInterface',
                    class_implements($modelSearchClass))) {
                $this->modelSearchClass = $modelSearchClass;
                $this->backendModule    = $backendModule;
            }
        }

        $numElementsPerPage = $parms["numElementsPerPage"];
        $withPagination     = $parms["withPagination"];
        $withoutSearch      = $parms["withoutSearch"];
        $cssClass           = $parms["cssClass"];
        $viewFieldsName     = $this->parseViewFields($parms["viewFields"]); //I campi che da cms si è deciso di visualizzare
        $listPage           = $parms["listPage"];
        $methodSearch       = $parms["methodSearch"];

        if (!$listPage) {
            $listPage = "list";
        }

        if ($this->modelSearchClass) {
            $searchModel = new $this->modelSearchClass();
            $limit       = null;
            if (!$withPagination) {
                $limit = $numElementsPerPage;
            }
            if (empty(trim($methodSearch))) {
                $methodSearch = "cmsSearch";
            }

            $dataProvider = $searchModel->$methodSearch($parms, $limit);
            if (!is_null($dataProvider)) {
                if ($withPagination) {
                    $pagination = $dataProvider->getPagination();
                    if (!$pagination) {
                        $pagination = new Pagination();
                        $dataProvider->setPagination($pagination);
                    }
                    $pagination->route = Yii::$app->request->getPathInfo();
                    $pagination->setPageSize($numElementsPerPage);
                } else {
                    $dataProvider->setPagination(FALSE);
                }
            }
            $globalViewFields = $searchModel->cmsViewFields(); // Tutti i campi che è possibile visualizzare

            $arrayViewFields = []; //I campi che vengono effettivamente visualizzati nella lista
            if ($viewFieldsName) {
                foreach ($globalViewFields as $field) {
                    if (in_array($field->name, $viewFieldsName)) {
                        $arrayViewFields[] = $field;
                    }
                }
            } else {// Se da cms non viene scelto nessun campo da visualizzare, si visualizzano tutti i campi
                $arrayViewFields = $globalViewFields;
            }

            $searchFields                   = $searchModel->cmsSearchFields();
            $blockItemId                    = !empty($parms["blockItemId"]) ? $parms["blockItemId"]
                    : Yii::$app->request->getQueryParam('blockItemId');
            Yii::$app->request->queryParams = array_merge(["parms" => $parms],
                Yii::$app->request->queryParams);
            return $this->render($listPage,
                    [
                    'model' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'cssClass' => $cssClass,
                    'detailPage' => $this->detailPage,
                    'viewFields' => $arrayViewFields,
                    'searchFields' => $searchFields,
                    'withoutSearch' => $withoutSearch,
                    'blockItemId' => $blockItemId
            ]);
        } else {
            return $this->goHome();
        }
    }

    /**
     * @param null $slug
     * @param null $blockItemId
     * @param bool $relatedDitailPage
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDetail($slug = null, $blockItemId = null,
                                 $relatedDitailPage = false)
    {

        $navItemPageBlockItem = \luya\cms\models\NavItemPageBlockItem::findOne([
                'id' => $blockItemId]);

        $model = \open20\amos\seo\AmosSeo::getModelFromPrettyUrl($slug);

        if (!$navItemPageBlockItem || !$model) {
            throw new \yii\web\NotFoundHttpException();
        }

        //$this->layout = '@frontend/views/layouts/'.$navItemPageBlockItem->navItemPage->layout->view_file;
        $block = $navItemPageBlockItem->block->classObject;
        if (!$block) {
            throw new \yii\web\NotFoundHttpException();
        }
        $block->setVarValues(json_decode($navItemPageBlockItem->json_config_values,
                true));
        $view = $relatedDitailPage ? $block->getVarValue('relatedDetailPage') : $block->getVarValue('detailPage');

        $modelSearchClass = $block->getVarValue('backendModule')::getModelSearchClassName();
        $isModelVisible   = (new $modelSearchClass)->cmsIsVisible($model->id);
        $modelClass       = $relatedDitailPage ? $model->className() : $block->getVarValue('backendModule')::getModelClassName();

        if (!empty($relatedDitailPage)) {
            if (!$isModelVisible || $modelClass != get_class($model)) {
                // il blocco fornito non corrisponde alla classe del model recuperato dallo slug
                throw new \yii\web\NotFoundHttpException();
            }
        }
        if ($model->getOgTitle()) {
            $this->view->registerMetaTag(['name' => 'og:title', 'content' => $model->getOgTitle()],
                'fbTitle');
            $this->view->registerMetaTag(['property' => 'og:title', 'content' => $model->getOgTitle()],
                'fbTitle');
        }
        if ($model->getOgDescription()) {
            $this->view->registerMetaTag(['name' => 'og:description', 'content' => $model->getOgDescription()],
                'fbDescription');
            $this->view->registerMetaTag(['property' => 'og:description', 'content' => $model->getOgDescription()],
                'fbDescription');
        }
        if ($model->getOgType()) {
            $this->view->registerMetaTag(['name' => 'og:type', 'content' => $model->getOgType()],
                'ogType');
            $this->view->registerMetaTag(['property' => 'og:type', 'content' => $model->getOgType()],
                'ogType');
        }
        if ($model->getOgImageUrl()) {
            $this->view->registerMetaTag(['name' => 'og:image', 'content' => $model->getOgImageUrl()],
                'ogImage');
            $this->view->registerMetaTag(['property' => 'og:image', 'content' => $model->getOgImageUrl()],
                'ogImage');
        }
        if ($model->getMetaRobots()) {
            $this->view->registerMetaTag(['name' => 'robots', 'content' => $model->getMetaRobots()],
                'metaRobots');
            $this->view->registerMetaTag(['property' => 'robots', 'content' => $model->getMetaRobots()],
                'metaRobots');
        }
        if ($model->getMetaGooglebot()) {
            $this->view->registerMetaTag(['name' => 'googlebot', 'content' => $model->getMetaGooglebot()],
                'metaGooglebot');
            $this->view->registerMetaTag(['property' => 'googlebot', 'content' => $model->getMetaGooglebot()],
                'metaGooglebot');
        }
        if ($model->getMetaDescription()) {
            $this->view->registerMetaTag(['name' => 'description', 'content' => $model->getMetaDescription()],
                'metaDescription');
            $this->view->registerMetaTag(['property' => 'description', 'content' => $model->getMetaDescription()],
                'metaDescription');
        }
        if ($model->getMetaKeywords()) {
            $this->view->registerMetaTag(['name' => 'keywords', 'content' => $model->getMetaKeywords()],
                'metyKeywords');
            $this->view->registerMetaTag(['property' => 'keywords', 'content' => $model->getMetaKeywords()],
                'metyKeywords');
        }
        if ($model->getMetaTitle()) {
            $this->view->title = $model->getMetaTitle();
        }

        $menuItem = Yii::$app->menu->findOne(['id' => $navItemPageBlockItem->navItemPage->navItem->id]);
        if ($menuItem) {
            Yii::$app->menu->setCurrent($menuItem);
        }
        if ($this->isAmp()) {
            $this->layout = "@app/views/layouts/main_amp";
        }
        $model_name = end(explode("\\", $modelClass));
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post(), $model_name);
        } elseif (Yii::$app->request->isGet) {
            $model->load(Yii::$app->request->get(), $model_name);
        }
        return $this->render($view,
                [
                    'model' => $model,
                    'blockItemId' => $blockItemId
        ]);
    }

    /**
     * @param $sourceArray
     * @return array
     */
    private function parseViewFields($sourceArray)
    {
        $targetArray = [];

        if ($sourceArray) {
            foreach ($sourceArray as $arrayVal) {
                $targetArray[] = $arrayVal["value"];
            }
        }

        return $targetArray;
    }

    /**
     *
     * @param type $id
     * @param type $blockItemId
     * @return type
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRestDetail($id, $blockItemId = null)
    {

        $navItemPageBlockItem = \luya\cms\models\NavItemPageBlockItem::findOne([
                'id' => $blockItemId]);
        //$this->layout = '@frontend/views/layouts/'.$navItemPageBlockItem->navItemPage->layout->view_file;
        $block                = $navItemPageBlockItem->block->classObject;
        if (!$block) {
            throw new \yii\web\NotFoundHttpException();
        }
        $block->setVarValues(json_decode($navItemPageBlockItem->json_config_values,
                true));

        $modelSearchClass = $block->getVarValue('backendModule')::getModelSearchClassName();
        $modelClass       = $block->getVarValue('backendModule')::getModelClassName();

        $model = $modelClass::findOne($id);

        if (!$navItemPageBlockItem || !$model) {
            throw new \yii\web\NotFoundHttpException();
        }


        $view           = $block->getVarValue('detailPage');
        $isModelVisible = (new $modelSearchClass)->cmsIsVisible($id);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
        }

        $menuItem = Yii::$app->menu->findOne(['id' => $navItemPageBlockItem->navItemPage->navItem->id]);
        if ($menuItem) {
            Yii::$app->menu->setCurrent($menuItem);
        }
        if ($this->isAmp()) {
            $this->layout = "@app/views/layouts/main_amp";
        }
        $model_name = end(explode("\\", $modelClass));
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post(), $model_name);
        } elseif (Yii::$app->request->isGet) {
            $model->load(Yii::$app->request->get(), $model_name);
        }
        return $this->render($view,
                [
                    'model' => $model,
                    'blockItemId' => $blockItemId
        ]);
    }
}