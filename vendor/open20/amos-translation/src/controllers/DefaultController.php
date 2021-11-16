<?php

namespace open20\amos\translation\controllers;

use open20\amos\translation\utility\TranslationUtility;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Cookie;
use open20\amos\translation\models\TranslationConf;
use yii\data\ActiveDataProvider;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;
use open20\amos\translation\AmosTranslation;
use yii\helpers\Url;
use open20\amos\admin\models\UserProfile;
use open20\amos\translation\models\TranslationUserLanguageMm;
use yii\web\ForbiddenHttpException;
use open20\amos\core\record\CachedActiveQuery;

/**
 * Translation controller
 */
class DefaultController extends Controller
{
    public $defaultAction = 'index';

    /**
     * @var string $layout Layout for the dashboard
     */
    public $layout = 'main';

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
                        'actions' => ['language', 'error', 'contents', 'records', 'update', 'index', 'translators', 'user-language',
                            'view-tr'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {

        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    public function actionIndex()
    {
        return $this->redirect(['/translation/translate/index']);
    }

    public function actionLanguage()
    {
        $data    = Yii::$app->request->post();
        $dataGet = Yii::$app->request->get();

        if (!empty($data['language'])) {
            \Yii::$app->language = $data['language'];
            $this->setLanguageCookie($data);

        } else if (!empty($dataGet['language'])) {
            $data = Yii::$app->request->get();
            \Yii::$app->language = $dataGet['language'];
            $this->setLanguageCookie($dataGet);
        }
        if (!empty($data['url'])) {
            return $this->redirect([$data['url']]);
        } else if (!empty($dataGet['url'])) {
            return $this->redirect([$dataGet['url']]);
        }
        $referrer = \Yii::$app->request->referrer;
        if (!empty($referrer)) {
            return $this->redirect(\yii\helpers\Url::to($referrer, true));
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param $data
     */
    public function setLanguageCookie($data){
        $module = \Yii::$app->getModule('translation');
        if($module && $module->secureCookie){
            $languageCookie = new Cookie([
                'name' => 'language',
                'value' => $data['language'],
                'expire' => time() + 60 * 60 * 24 * 30, // 30 days
            ]);
            \Yii::$app->response->cookies->add($languageCookie);
        }
        else {
            $domain = null;
            if($module->enableCookieFor2LevelDomain) {
                $host = $_SERVER['HTTP_HOST'];
                $exploded = explode('.', $host);
                if (count($exploded) >= 2) {
                    $sliced = array_slice($exploded, -2, 2);
                    $domain = '.' . $sliced[0] . '.' . $sliced[1];
                }
            }
            setcookie('language', $data['language'], time() + 60 * 60 * 24 * 30, '/', $domain);
        }
    }

    public function actionContents()
    {
        $model = new TranslationConf();

        $params = Yii::$app->getRequest()->getQueryParams();
        $query  = TranslationConf::getTranslateContents($params);
        if (!empty($params)) {
            $model->load($params);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $this->render('contents',
                [
                'dataProvider' => $dataProvider,
                'model' => $model
        ]);
    }

    public function actionTranslators()
    {
        $model = UserProfile::find()
            ->leftJoin('translation_user_language_mm as L', 'user_profile.user_id = L.user_id')
            ->orderBy('L.user_id');

        $userIds = [];
        foreach ($model->all() as $value) {
            if (\Yii::$app->authManager->checkAccess($value->id, 'CONTENT_TRANSLATOR')) {
                $userIds[] = $value->id;
            }
        }

        $model->andWhere(['in', 'id', $userIds]);


        $dataProvider = new ActiveDataProvider([
            'query' => $model,
        ]);


        return $this->render('translators', [
                'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUserLanguage($user_id)
    {
        $userProfile = UserProfile::find()
            ->andWhere(['user_id' => $user_id]);

        $data = \Yii::$app->request->post();
        if ($data) {
            TranslationUserLanguageMm::deleteAll(['user_id' => $user_id]);
            $selected = (!empty($data['TranslationUserLanguageMm']['language']) ? $data['TranslationUserLanguageMm']['language']
                    : []);
            foreach ($selected as $value) {
                $newModel           = new TranslationUserLanguageMm();
                $newModel->user_id  = $user_id;
                $newModel->language = $value;
                $newModel->save(false);
            }
        }

        $model = TranslationUserLanguageMm::findOne(['user_id' => $user_id]);
        if (!$model) {
            $model = new TranslationUserLanguageMm();
        }

        $languages = \open20\amos\translation\models\TranslationUserLanguageMm::find()
            ->andWhere(['user_id' => $user_id])
            ->select('language');
        $lngs      = [];
        foreach ($languages->all() as $v) {
            $lngs[] = $v->language;
        }
        $model->language = $lngs;

        return $this->render('user_language',
                [
                'model' => $model,
                'languages' => $languages,
                'userProfile' => $userProfile
        ]);
    }

    /**
     * @param $namespace
     * @param $lang
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionRecords($namespace, $lang)
    {
        $module = \Yii::$app->controller->module;
        if (!\Yii::$app->getUser()->can('TRANSLATOR', ['model' => new TranslationUserLanguageMm(), 'language' => $lang])
            ||
            !((!empty($module->defaultLanguage) && $lang != $module->defaultLanguage) || (!isset($module->defaultLanguage)))
        ) {
            throw new ForbiddenHttpException(Yii::t('amoscore', 'Access denied.'));
        }
        $classNameTrans = $module->modelNs.'\\'.StringHelper::basename($namespace)."Translation";

        $lang = $this->verifyLang($lang);

        $modelSearch = new \open20\amos\translation\models\search\TranslationSearch();
        $model    = new $classNameTrans;
        $pkSource = \Yii::$app->{$module->dbSource}->getTableSchema($namespace::tableName())->primaryKey;

        $columns     = TranslationConf::getColumnsRecord($namespace, $lang);
        $tableSource = $namespace::tableName();
        $tableTrans  = $classNameTrans::tableName();

        $pk   = $model->getPrimaryKey();
        reset($pk);
        $pkId = key($pk);

        $selectsTrans  = [];
        $selectsSource = [];

        foreach ($columns as $sel) {
            if (is_array($sel) && isset($sel['attribute']) && $sel['attribute'] != $pkId) {
                $selectsTrans[]  = "{$tableTrans}.{$sel['attribute']}";
                $selectsSource[] = "{$tableSource}.{$sel['attribute']}";
            }
        }


        $newExp = new \yii\db\Expression("$tableSource.{$pkSource[0]} as '$pkId'");

        $newExpLang = new \yii\db\Expression("{$tableTrans}.{$module->languageField} as 'languageField'");
        try {
            $query = (new \yii\db\Query())->from($tableSource)
                ->leftJoin($tableTrans,
                    "{$tableSource}.{$pkSource[0]} = {$tableTrans}.{$pkId} AND {$tableTrans}.{$module->languageField} = '$lang'")
                ->andWhere(["$tableSource.deleted_by" => null])
                ->select(array_merge([$newExp], $selectsTrans));
        } catch (\Exception $ex) {
            $query = (new \yii\db\Query())->from($tableSource)
                ->leftJoin($tableTrans,
                    "{$tableSource}.{$pkSource[0]} = {$tableTrans}.{$pkId} AND {$tableTrans}.{$module->languageField} = '$lang'")
                ->select(array_merge([$newExp], $selectsTrans));
        }
        $query2 = $namespace::find()->select(array_merge([$newExp], $selectsSource));
        $query2->union($query)->groupBy($pkId);


        // SEARCH TRANSLATION
        /** @var  $attributesToTranslate*/
        $attributesToTranslate = TranslationUtility::getAttributesToTranslate($namespace);
        $modelSearch->load(\Yii::$app->request->get());
        if(isset($modelSearch->isTranslated) && ($modelSearch->isTranslated == 1 || $modelSearch->isTranslated ==2)){
            if($modelSearch->isTranslated == 2){
                $isCondition = 'IS NOT';
            }
            else {
                $isCondition = 'IS';
            }
            foreach ($attributesToTranslate as $attribute){
                $query->andWhere([$isCondition, $tableTrans.'.'.$attribute, NULL]);
            }
        }

        foreach ($attributesToTranslate as $attribute){
           $value = \Yii::$app->request->get($attribute);
           $modelSearch->attributes [$attribute] = $value;
           if(!empty($value)){
               $query->andWhere([ 'OR',
                   ['LIKE', $tableSource.'.'.$attribute, $value],
                   [
                       'AND',
                       ['LIKE', $tableTrans.'.'.$attribute, $value],
                       [ $tableTrans.'.'.'language' => $lang],
                   ]
               ]);
           }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('records',
                [
                'dataProvider' => $dataProvider,
                'model' => $model,
                'modelSearch' => $modelSearch,
                'columns' => $columns,
                'classname' => Inflector::camel2words(StringHelper::basename($namespace)),
                'lang' => $lang,
                'pk' => $pkId,
        ]);
    }

    public function actionUpdate($id, $lang, $namespace, $url = null)
    {
        if (!\Yii::$app->getUser()->can('TRANSLATOR', ['model' => new TranslationUserLanguageMm(), 'language' => $lang])) {
            throw new ForbiddenHttpException(Yii::t('amoscore', 'Access denied.'));
        }
        $module          = \Yii::$app->controller->module;
        $classNameTrans  = $module->modelNs.'\\'.StringHelper::basename($namespace)."Translation";
        $language_source = null;

        $lang = $this->verifyLang($lang);

        $model         = new $classNameTrans;
        $rteAttributes = $this->getRteAttributesByNamespace($namespace);

        $pk   = $model->getPrimaryKey();
        reset($pk);
        $pkId = key($pk);

        $language_source = \Yii::$app->request->getQueryParam(StringHelper::basename($classNameTrans))['language_source'];

        list($language_source_res, $source) = TranslationConf::getSource($language_source, $id, $lang, $namespace);

        TranslationConf::setTranslation($id, $lang, $namespace);
        list($stringField, $textField) = TranslationConf::getFields($namespace, $lang);

        $modelTrans = $model->findOne([$pkId => $id, $module->languageField => $lang]);

        if ($language_source) {
            if ($language_source_res) {
                $modelTrans->language_source = $language_source;
            } else {
                \Yii::$app->getSession()->addFlash('warning',
                    AmosTranslation::t('amostranslation', 'The content is not translated into').' '.$language_source);
            }
        }

        if ($modelTrans->load(\Yii::$app->request->post())) {
            $modelTrans->save(false);

            if ($module->cached) {
                CachedActiveQuery::reset($module->queryCache);
            }
            return $this->redirect(['update', 'id' => $id, 'lang' => $lang, 'namespace' => $namespace, 'url' => $url]);
        }


        return $this->render('update',
                [
                'id' => $id,
                'source' => $source->one(),
                'model' => $modelTrans,
                'modelClassName' => $classNameTrans,
                'stringField' => $stringField,
                'textField' => $textField,
                'namespace' => $namespace,
                'rte' => $module->enableRTE,
                'rteAttributes' => $rteAttributes,
                'classname' => Inflector::camel2words(StringHelper::basename($namespace)),
                'lang' => $lang,
                'pk' => $pkId,
                'url' => $url,
                'fieldLanguage' => $module->languageField
        ]);
    }

    public function actionViewTr($id, $lang, $namespace, $url = null)
    {
        $module          = \Yii::$app->controller->module;
        $classNameTrans  = $module->modelNs.'\\'.StringHelper::basename($namespace)."Translation";
        $language_source = null;

        $lang = $this->verifyLang($lang);

        $model         = new $classNameTrans;
        $rteAttributes = $this->getRteAttributesByNamespace($namespace);

        $pk   = $model->getPrimaryKey();
        reset($pk);
        $pkId = key($pk);

        $language_source = \Yii::$app->request->getQueryParam(StringHelper::basename($classNameTrans))['language_source'];

        list($language_source_res, $source) = TranslationConf::getSource($language_source, $id, $lang, $namespace);

        TranslationConf::setTranslation($id, $lang, $namespace);
        list($stringField, $textField) = TranslationConf::getFields($namespace, $lang);

        $modelTrans = $model->findOne([$pkId => $id, $module->languageField => $lang]);

        if ($language_source) {
            if ($language_source_res) {
                $modelTrans->language_source = $language_source;
            } else {
                \Yii::$app->getSession()->addFlash('warning',
                    AmosTranslation::t('amostranslation', 'The content is not translated into').' '.$language_source);
            }
        }
        if ($modelTrans->load(\Yii::$app->request->post())) {
            $modelTrans->save(false);
            return $this->redirect(['view-tr', 'id' => $id, 'lang' => $lang, 'namespace' => $namespace, 'url' => $url]);
        }



        return $this->render('view_tr',
                [
                'id' => $id,
                'source' => $source->one(),
                'model' => $modelTrans,
                'modelClassName' => $classNameTrans,
                'stringField' => $stringField,
                'textField' => $textField,
                'namespace' => $namespace,
                'rte' => $module->enableRTE,
                'rteAttributes' => $rteAttributes,
                'classname' => Inflector::camel2words(StringHelper::basename($namespace)),
                'lang' => $lang,
                'pk' => $pkId,
                'url' => $url,
                'fieldLanguage' => $module->languageField
        ]);
    }

    private function verifyLang($lang)
    {
        $module = \Yii::$app->controller->module;

        $activeLang    = TranslationConf::getStaticAllActiveLanguages();
        $allActiveLang = [];
        foreach ($activeLang->all() as $lng) {
            $allActiveLang[] = $lng->language_id;
        }
        if (!in_array($lang, $allActiveLang)) {
            $lang = (isset($module->defaultLanguage) && in_array($module->defaultLanguage, $allActiveLang)) ? $module->defaultLanguage
                    : \Yii::$app->language;
        }
        return $lang;
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module       = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/'.(!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }
        return true;
    }

    /**
     * Return the attributes will enable the RTE
     * @param string $namespace
     * @return array
     */
    public function getRteAttributesByNamespace($namespace)
    {
        $rteAttributes = [];
        $module        = \Yii::$app->controller->module;
        if (!empty($module->translationBootstrap['configuration'])) {
            if (!empty($module->translationBootstrap['configuration']['translationContents'])) {
                if (!empty($module->translationBootstrap['configuration']['translationContents']['models'])) {
                    $models = $module->translationBootstrap['configuration']['translationContents']['models'];
                    foreach ($models as $k => $v) {
                        if ($v['namespace'] == $namespace) {
                            if (!empty($v['rteAttributes']) && is_array($v['rteAttributes'])) {
                                $rteAttributes = $v['rteAttributes'];
                            }
                        }
                    }
                }
            }
        }
        return $rteAttributes;
    }
}