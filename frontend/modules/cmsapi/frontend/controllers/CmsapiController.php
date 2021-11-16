<?php

namespace app\modules\cmsapi\frontend\controllers;

use app\modules\cms\admin\Module;
use app\modules\cms\models\Nav;
use app\modules\cms\models\NavItem;
use app\modules\cmsapi\frontend\controllers\PreviewController;
use app\modules\cmsapi\frontend\models\CmsMailAfterLogin;
use app\modules\cmsapi\frontend\models\CmsResultCreatePage;
use app\modules\cmsapi\frontend\models\PostCmsCreatePage;
use app\modules\cmsapi\frontend\utility\CmsLandigBuilder;
use dosamigos\qrcode\QrCode;
use open20\amos\discussioni\models\search\DiscussioniTopicSearch;
use open20\amos\emailmanager\AmosEmail;
use open20\amos\news\models\search\NewsSearch;
use luya\admin\models\Lang;
use luya\cms\admin\helpers\MenuHelper;
use luya\helpers\ArrayHelper;
use luya\helpers\FileHelper;
use luya\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class CmsapiController extends Controller
{
    private static $TKS_TITLE     = "Tks Page ";
    private static $WAITING_TITLE = "Waiting Page ";
    private static $ALREADY_TITLE = "Already Present Page ";
    private $tks_template_page_id;
    private $waiting_template_page_id;
    private $already_present_template_page_id;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionQrcode($base64_code)
    {

        return QrCode::png(base64_decode($base64_code));
    }

    public function actionNews()
    {
        $limit        = 3;
        $newsSearch   = new NewsSearch();
        $dataprovider = $newsSearch->cmsSearch($limit);
        $dataprovider->query->limit($limit)->orderBy(['data_pubblicazione' => SORT_DESC]);

        $itemMenu = '';

        foreach ($dataprovider->query->all() as $model) {
            $url = \Yii::$app->getModule('backendobjects')::getSeoUrl($model->getPrettyUrl(),
                    572, false, false);

            $itemMenu .= '<li>
            <a class="" title="'.$model->titolo.'" href="'.$url.'">';
            if (isset($model->data_pubblicazione) && !empty($model->data_pubblicazione)) {
                $itemMenu .= $model->titolo.' | '.Yii::$app->getFormatter()->asDate($model->data_pubblicazione);
            } else {
                $itemMenu .= $model->titolo;
            }
            $itemMenu .= '</a> 
			
			</li>';
        }
        $itemMenu .= '<li> <a class="" href="/it/lista-news"> Vedi tutti</a> </li>';
        return $itemMenu;
    }

    public function actionCommunity()
    {
        $limit             = 3;
        $discussioniSearch = new DiscussioniTopicSearch();
        $dataprovider      = $discussioniSearch->cmsSearch(Yii::$app->request->get(),
            $limit);
        $query             = $dataprovider->query;
        $query->limit($limit);

        $itemMenu = '';

        foreach ($dataprovider->query->all() as $model) {
            $url      = \Yii::$app->getModule('backendobjects')::getSeoUrl($model->getPrettyUrl(),
                    597, false, false);
            $itemMenu .= '<li>
            <a class="" title="'.$model->titolo.'" href="'.$url.'">';
            $itemMenu .= $model->titolo;
            $itemMenu .= '</a> 
			</li>';
        }
        $itemMenu .= '<li> <a class="" href="/it/lista-discussioni"> Vedi tutti</a> </li>';
        return $itemMenu;
    }

    public function actionRedazione()
    {
        $limit        = 3;
        $newsSearch   = new NewsSearch();
        $dataprovider = $newsSearch->cmsSearchRedazione($limit);
        $dataprovider->query->limit($limit)->orderBy(['data_pubblicazione' => SORT_DESC]);

        $itemMenu = '';

        foreach ($dataprovider->query->all() as $model) {
            $url      = \Yii::$app->getModule('backendobjects')::getSeoUrl($model->getPrettyUrl(),
                    573, false, false);
            $itemMenu .= '<li>
            <a class="" title="'.$model->titolo.'" href="'.$url.'">';
            if (isset($model->data_pubblicazione) && !empty($model->data_pubblicazione)) {
                $itemMenu .= $model->titolo.' | '.Yii::$app->getFormatter()->asDate($model->data_pubblicazione);
            } else {
                $itemMenu .= $model->titolo;
            }
            $itemMenu .= '</a>
			</li>';
        }
        $itemMenu .= '<li> <a class="" href="/it/lista-news-redazione"> Vedi tutti</a> </li>';
        return $itemMenu;
    }

    /**
     * Get the file content response for a given key.
     *
     * @param string $key
     * @throws ForbiddenHttpException
     * @return Response
     */
    public function actionExportDownload($key)
    {
        $sessionkey = Yii::$app->session->get('tempNgRestFileKey');
        $fileName   = Yii::$app->session->get('tempNgRestFileName');
        $mimeType   = Yii::$app->session->get('tempNgRestFileMime');

        if ($sessionkey !== base64_decode($key)) {
            throw new ForbiddenHttpException('Invalid download key.');
        }

        $content = FileHelper::getFileContent('@runtime/'.$sessionkey.'.tmp');

        Yii::$app->session->remove('tempNgRestFileKey');
        Yii::$app->session->remove('tempNgRestFileName');
        Yii::$app->session->remove('tempNgRestFileMime');
        @unlink(Yii::getAlias('@runtime/'.$sessionkey.'.tmp'));
        return Yii::$app->response->sendContentAsFile($content, $fileName,
                ['mimeType' => $mimeType]);
    }

    /**
     *
     * @param type $id
     * @param type $redirect
     * @return type
     */
    public function actionSendMailAfterLogin($id, $redirect)
    {
        $appLink    = \Yii::$app->params['platform']['backendUrl'].'/';
        $link       = $appLink.$redirect;
        $result     = false;
        /** @var AmosEmail $mailModule */
        $mailModule = \Yii::$app->getModule("email");
        if (isset($mailModule)) {
            $cms_after_login = CmsMailAfterLogin::findOne($id);
            if (!empty($cms_after_login)) {
                $from = $cms_after_login->email_from;
                $tos  = [$cms_after_login->email_to];

                $subject                   = $cms_after_login->subject;
                $text                      = $cms_after_login->body;
                $mailModule->defaultLayout = $cms_after_login->layout_email;
                $result                    = $mailModule->send($from, $tos,
                    $subject, $text, [], [], []);
                return $this->redirect($link);
            }
        }
        return $this->redirect($redirect);
    }

    /**
     * Create Cms Page.
     *
     * @return CmsResultCreatePage
     */
    public function actionCreatePage()
    {
        $result                      = new CmsResultCreatePage();
        $request                     = Yii::$app->request;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isAjax) {
            if ($request->isPost) {
                $this->menuFlush();
                $postCmsPage = new PostCmsCreatePage();
                if (!is_null($postCmsPage)) {
                    if ($postCmsPage->load($request->post(), '') && $postCmsPage->validate()) {
                        Module::setBackendUserId($postCmsPage->cms_user_id);
                        $model = $this->createPage($postCmsPage);
                        if (is_null($model)) {
                            Yii::$app->response->statusCode = 422;
                        } else {
                            $result->nav_id      = $model->id;
                            $result->preview_url = $model->getPreviewUrl();
                            $postCmsPage->nav_id = $model->id;

                            $landing = new CmsLandigBuilder([
                                ], $postCmsPage);
                            $data    = $landing->getDataFromTemplate($postCmsPage->from_draft_id);
                            if (!is_null($data)) {
                                $this->already_present_template_page_id = $data->already_present_template_page_id;
                                $this->tks_template_page_id             = $data->tks_template_page_id;
                                $this->waiting_template_page_id         = $data->waiting_template_page_id;
                            }


                            $modeltksP = $this->createTksPage($postCmsPage);
                            if (!is_null($modeltksP)) {
                                $postCmsPage->form_landing->nav_id_tks_page = $modeltksP->id;
                                $result->nav_id_tks_page                    = $modeltksP->id;
                            }
                            $modeltksP = $this->createWaitingPage($postCmsPage);
                            if (!is_null($modeltksP)) {
                                $postCmsPage->form_landing->nav_id_wating_page = $modeltksP->id;
                                $result->nav_id_wating_page                    = $modeltksP->id;
                            }
                            $modeltksP = $this->createAlreadyPage($postCmsPage);
                            if (!is_null($modeltksP)) {
                                $postCmsPage->form_landing->nav_id_already_present_page
                                    = $modeltksP->id;
                                $result->nav_id_already_present_page                    = $modeltksP->id;
                            }

                            $landing->buidTks();
                            $landing->buildWaiting();
                            $landing->buildAlready();
                            $landing->build();
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     *
     * @param PostCmsCreatePage $postCmsPage
     * @return Nav
     */
    protected function createTksPage(PostCmsCreatePage $postCmsPage)
    {
        $ret = null;
        if (!empty($this->tks_template_page_id)) {
            $postCmsPageTks                = clone $postCmsPage;
            $postCmsPageTks->use_draft     = true;
            $postCmsPageTks->from_draft_id = $this->tks_template_page_id;
            $postCmsPageTks->title         = static::$TKS_TITLE.$postCmsPageTks->title;
            $postCmsPageTks->alias         = static::$TKS_TITLE.$postCmsPageTks->alias;

            $ret = $this->createPage($postCmsPageTks);
        }
        return $ret;
    }

    /**
     *
     * @param PostCmsCreatePage $postCmsPage
     * @return Nav
     */
    protected function createWaitingPage(PostCmsCreatePage $postCmsPage)
    {
        $ret = null;

        if (!empty($this->waiting_template_page_id)) {
            $postCmsPageWait                = clone $postCmsPage;
            $postCmsPageWait->use_draft     = true;
            $postCmsPageWait->from_draft_id = $this->waiting_template_page_id;
            $postCmsPageWait->title         = static::$WAITING_TITLE.$postCmsPageWait->title;
            $postCmsPageWait->alias         = static::$WAITING_TITLE.$postCmsPageWait->alias;

            $ret = $this->createPage($postCmsPageWait);
        }
        return $ret;
    }

    /**
     *
     * @param PostCmsCreatePage $postCmsPage
     * @return Nav
     */
    protected function createAlreadyPage(PostCmsCreatePage $postCmsPage)
    {
        $ret = null;
        if (!empty($this->already_present_template_page_id)) {
            $postCmsPageAlready                = clone $postCmsPage;
            $postCmsPageAlready->use_draft     = true;
            $postCmsPageAlready->from_draft_id = $this->already_present_template_page_id;
            $postCmsPageAlready->title         = static::$ALREADY_TITLE.$postCmsPageAlready->title;
            $postCmsPageAlready->alias         = static::$ALREADY_TITLE.$postCmsPageAlready->alias;

            $ret = $this->createPage($postCmsPageAlready);
        }
        return $ret;
    }

    /**
     *
     * @param PostCmsCreatePage $postCmsPage
     * @return Nav
     */
    protected function createPage(PostCmsCreatePage $postCmsPage): Nav
    {
        $create = "";

        $this->menuFlush();
        $model          = new Nav();
        $fromDraft      = $postCmsPage->use_draft;
        $parentNavId    = $postCmsPage->parent_nav_id;
        $navContainerId = $postCmsPage->nav_container_id;

        if (!empty($parentNavId)) {
            $navContainerId = Nav::findOne($parentNavId)->nav_container_id;
        }

        if (!empty($fromDraft)) {
            $create = $model->createPageFromDraft($parentNavId, $navContainerId,
                $postCmsPage->lang_id, $postCmsPage->title, $postCmsPage->alias,
                $postCmsPage->description, $postCmsPage->from_draft_id,
                $postCmsPage->is_draft);
        } else {
            $create = $model->createPage($parentNavId, $navContainerId,
                $postCmsPage->lang_id, $postCmsPage->title, $postCmsPage->alias,
                $postCmsPage->layout_id, $postCmsPage->description,
                $postCmsPage->is_draft);
        }
        return $model;
    }

    /**
     * Update Cms Page.
     *
     * @return CmsResultCreatePage
     */
    public function actionUpdatePage()
    {
        $result                      = new CmsResultCreatePage();
        $request                     = Yii::$app->request;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isAjax) {
            if ($request->isPost) {
                $this->menuFlush();
                $model       = new Nav();
                $postCmsPage = new PostCmsCreatePage();
                if (!is_null($postCmsPage)) {
                    if ($postCmsPage->load($request->post(), '') && $postCmsPage->validate()) {
                        Module::setBackendUserId($postCmsPage->cms_user_id);
                        if (!empty($postCmsPage->nav_id)) {
                            $model = Nav::findOne($postCmsPage->nav_id);
                            if (!is_null($model)) {

                                $result->nav_id      = $model->id;
                                $result->preview_url = $model->getPreviewUrl();
                                $landing             = new CmsLandigBuilder([],
                                    $postCmsPage);
                                $landing->buidTks();
                                $landing->buildWaiting();
                                $landing->buildAlready();
                                $landing->build();
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @return bool|mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeletePage(){
        $result                      = false;
        $request                     = Yii::$app->request;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isAjax) {
            if ($request->isPost) {
                $this->menuFlush();
                $postCmsPage = new PostCmsCreatePage();
                if (!is_null($postCmsPage)) {
                    if ($postCmsPage->load($request->post(), '') && $postCmsPage->validate()) {
                        Module::setBackendUserId($postCmsPage->cms_user_id);
                        if (!empty($postCmsPage->nav_id)) {
                            $model = Nav::findOne($postCmsPage->nav_id);
                            if (!is_null($model)) {
                                $result = $this->deletePage($model);
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param $model Nav
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deletePage($model){
        $model->navItems;
        /** @var  $navItem NavItem */
        foreach ($model->navItems as $navItem){
            $navItem->delete();
        }
        return $model->delete();
    }

    /**
     *
     * @return CmsResultCreatePage
     */
    public function actionPreviewPage()
    {
        $result                      = new CmsResultCreatePage();
        $request                     = Yii::$app->request;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isAjax) {
            if ($request->isPost) {
                $this->menuFlush();
                $postCmsPage = new PostCmsCreatePage();
                if (!is_null($postCmsPage)) {
                    if ($postCmsPage->load($request->post(), '') && $postCmsPage->validate()) {
                        if (!empty($postCmsPage->nav_id)) {
                            $model = Nav::findOne($postCmsPage->nav_id);
                            if (!is_null($model)) {
                                $result->nav_id      = $model->id;
                                $result->preview_url = $model->getPreviewUrl();
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Flush the menu data if component exits.
     *
     * 
     */
    protected function menuFlush()
    {
        if (Yii::$app->get('menu', false)) {
            Yii::$app->menu->flushCache();
        }
    }

    /**
     *
     * List of defined Templates in Cms.
     * @return json
     */
    public function actionListTemplates()
    {
        $drafts                      = [];
        $request                     = Yii::$app->request;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isAjax) {
            $drafts = $this->filterDrafts(MenuHelper::getDrafts());
        }
        return $drafts;
    }

    /**
     *
     * @param array $drafts
     */
    protected function filterDrafts($drafts)
    {
        $removeIds = [];
        $landing   = new CmsLandigBuilder();
        foreach ($drafts as $draft) {
            $data = $landing->getDataFromTemplate($draft['id']);
            if (!is_null($data)) {
                $removeIds[] = $data->already_present_template_page_id;
                $removeIds[] = $data->tks_template_page_id;
                $removeIds[] = $data->waiting_template_page_id;
            }
        }
        foreach ($drafts as $draft) {

            if (ArrayHelper::isIn($draft['id'], $removeIds)) {
                ArrayHelper::removeValue($drafts, $draft);
            }
        }
        return $drafts;
    }

    /**
     * List of defined languages in CMS.
     * @return json
     */
    public function actionListLanguages()
    {
        $languages                   = [];
        $request                     = Yii::$app->request;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isAjax) {
            $languages = Lang::getQuery();
        }
        return $languages;
    }

    /**
     *
     * @return string
     */
    public function actionGetPreviewPageHtml()
    {
        $result                      = "";
        $request                     = Yii::$app->request;
        \Yii::$app->response->format = Response::FORMAT_HTML;
        if ($request->isAjax) {
            if ($request->isPost) {
                $this->menuFlush();
                $postCmsPage = new PostCmsCreatePage();
                if (!is_null($postCmsPage)) {
                    if ($postCmsPage->load($request->post(), '') && $postCmsPage->validate()) {
                        if (!empty($postCmsPage->nav_id)) {
                            $model = Nav::findOne($postCmsPage->nav_id);
                            if (!is_null($model)) {
                                $navItem = NavItem::findOne(['nav_id' => $model->id,
                                        'lang_id' => $postCmsPage->lang_id]);
                                if (!is_null($navItem)) {
                                    $previewController = new PreviewController();
                                    $result            = $previewController->preview($navItem->id);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param $alias
     * @param null $nav_id
     * @return bool
     */
    public function actionIsNewAliasValid($alias, $nav_id = null){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $count = NavItem::find()
            ->andWhere(['alias' => $alias])
            ->andFilterWhere(['!=','nav_id', $nav_id])->count();
        return $count == 0;
    }
}