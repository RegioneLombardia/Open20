<?php

namespace amos\cmsbridge\utility;

use amos\cmsbridge\models\CmsResultCreatePage;
use amos\cmsbridge\models\CmsResultFormat;
use amos\cmsbridge\models\CmsResultHtmlPage;
use amos\cmsbridge\models\CmsResultPage;
use amos\cmsbridge\models\PostCmsCreatePage;
use open20\amos\mobile\bridge\modules\v1\models\User as TokenUser;
use Exception;
use Yii;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\log\Logger;

class CmsUtility
{
    protected $client;
    protected $loginCookies = null;

    /**
     * @var Module $moduleCms
     */
    protected $moduleCms = null;
    
    public function __construct()
    {
        $this->client = new Client();
        $this->moduleCms = Yii::$app->getModule('cmsbridge');
    }

    /**
     * Login on cms admin tool.
     *
     * @return integer
     */
    public function loginCms()
    {   
        $user_id = 0;

        try {
            $user      = TokenUser::findOne(Yii::$app->user->id);
            $token     = $user->refreshAccessToken('webcms', 'cms')->access_token;
            $url_front = $this->moduleCms->frontendUrl.'/admin/login/login-amos';

            $response = $this->client->createRequest()
                ->setMethod('GET')
                ->setUrl($url_front)
                ->setData(['secure_token' => $token])
                ->send();

            if ($response->isOk) {
                $values  = $this->decodeLoginData($response->content);
                $user_id = $values['user_id'];
                $this->loginCookies = clone $response->cookies;
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

        return $user_id;
    }

    /**
     *
     * @param string $data
     * @return array
     */
    private function decodeLoginData($data)
    {
        try {
            $data = str_replace('jCallback', '', $data);
            $data = str_replace('(', '', $data);
            $data = str_replace(')', '', $data);
            $data = str_replace('\'', '"', $data);
            return Json::decode($data);
        } catch (Exception $ex) {
            return [];
        }
    }

    /**
     * 
     * @param PostCmsCreatePage $page
     * @param string $url
     * @return CmsResultCreatePage
     */
    public function createCmsPage(PostCmsCreatePage $page, $url = null): CmsResultCreatePage
    {
        if(!is_null($url))
        {
            $url_front = $url;
        }else
        {
            $url_front = Yii::$app->params['platform']['frontendUrl'].'/api/1/create-page';
        }
        
        $url_front = $this->moduleCms->frontendUrl . '/api/1/create-page';
        return $this->pageRestClient($url_front, $page, CmsResultFormat::JSON);
    }

    /**
     *
     * @param PostCmsCreatePage $page
     * @return boolean
     */
    public function deleteCmsPage(PostCmsCreatePage $page): CmsResultCreatePage
    {
        $url_front = $this->moduleCms->frontendUrl.'/api/1/delete-page';
        return $this->pageRestClient($url_front, $page, CmsResultFormat::JSON);
    }
    

    /**
     *
     * @param PostCmsCreatePage $page
     * @return string
     */
    public function getPreviewUrlCmsPage(PostCmsCreatePage $page): CmsResultCreatePage
    {
        $url_front = $this->moduleCms->frontendUrl.'/api/1/preview-page';
        return $this->pageRestClient($url_front, $page, CmsResultFormat::JSON);
    }

    /**
     *
     * @param PostCmsCreatePage $page
     * @return CmsResultHtmlPage
     */
    public function getPreviewUrlCmsPageHtml(PostCmsCreatePage $page): CmsResultHtmlPage
    {
        $url_front = $this->moduleCms->frontendUrl.'/api/1/preview-page-html';
        return $this->pageRestClient($url_front, $page, CmsResultFormat::HTML);
    }

    /**
     *
     * @param PostCmsCreatePage $page
     * @return CmsResultCreatePage
     */
    public function updateCmsPage(PostCmsCreatePage $page): CmsResultCreatePage
    {
        $url_front = $this->moduleCms->frontendUrl.'/api/1/update-page';
        return $this->pageRestClient($url_front, $page, CmsResultFormat::JSON);
    }

    /**
     * 
     * @param string $url
     * @param PostCmsCreatePage $page
     * @return CmsResultCreatePage
     */
    protected function pageRestClient(string $url, PostCmsCreatePage $page,
                                      string $format): CmsResultPage
    {

        try {

            $response = $this->client->createRequest()
                ->setMethod('POST')
                ->setUrl($url)
                ->setCookies($this->loginCookies)
                ->setData([
                        'nav_id' => $page->nav_id,
                        'is_draft' => $page->is_draft,
                        'alias' => $page->alias,
                        'description' => $page->description,
                        'title' => $page->title,
                        'from_draft_id' => $page->from_draft_id,
                        'lang_id' => $page->lang_id,
                        'layout_id' => $page->layout_id,
                        'nav_container_id' => $page->nav_container_id,
                        'parent_nav_id' => $page->parent_nav_id,
                        'use_draft' => $page->use_draft,
                        'cms_user_id' => $page->cms_user_id,
                        'with_login' => $page->with_login,
                        'form_landing' => $page->form_landing->json_encode(),
                        'event_data' => $page->event_data->json_encode(),
                    ]
                )
                ->setHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                ->setOptions([
                    'timeout' => 600, // set timeout to 600 seconds for the case server is not responding
                ])
                ->send();
            $result = CmsResultFormat::format($format);
            $result->build($response->content);
            $result->status = $response->isOk;

//            $myfile = fopen("error_luya.txt", "a") or die("Unable to open file!");
//            $txt = "OK ----- ".date('d-m-Y H:i:s')."\n";
//            fwrite($myfile, $txt);
//            $txt = json_encode($result)."\n";
//            fwrite($myfile, $txt);
//            $txt = "-----------------------\n";
//            fwrite($myfile, $txt);
//            fclose($myfile);
        }catch (\yii\base\Exception $e){


            $myfile = fopen("error_luya.txt", "a") or die("Unable to open file!");
            $txt = "ERROR ----- ". date('d-m-Y H:i:s')."\n";
            fwrite($myfile, $txt);

            $txt = $e->getMessage()."\n";
            fwrite($myfile, $txt);
            $txt = $e->getTraceAsString()."\n";
            fwrite($myfile, $txt);
            $txt = $e->getFile().' '. $e->getLine()."\n";
            fwrite($myfile, $txt);
            $txt = '#####'.json_encode($result)."\n";
            fwrite($myfile, $txt);
            $txt = "-----------------------\n";
            fwrite($myfile, $txt);
            fclose($myfile);
            $result = new CmsResultCreatePage();

        }
        return $result;
    }

    /**
     *
     * @return array
     */
    public function getCmsTemplates()
    {
        $templates = [];

        $url_front = $this->moduleCms->frontendUrl.'/api/1/list-templates';

        $client   = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setCookies($this->loginCookies)
            ->setUrl($url_front)
            ->setData([])
            ->setHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->send();
        if ($response->isOk) {
            $templates = $response->data;
        }

        return $templates;
    }

    /**
     *
     * @return array
     */
    public function getCmsLanguages()
    {
        $languages = [];

        $url_front = $this->moduleCms->frontendUrl.'/api/1/list-languages';

        $client   = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url_front)
            ->setCookies($this->loginCookies)
            ->setData([])
            ->setHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->send();
        if ($response->isOk) {
            $languages = $response->data;
        }

        return $languages;
    }

    /**
     * @param $alias
     * @param null $nav_id
     * @return bool|mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function isUrlOk($alias, $nav_id = null)
    {

        $url_front = Yii::$app->params['platform']['frontendUrl'].'/api/1/is-new-alias-valid';

        $client   = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setCookies($this->loginCookies)
            ->setUrl($url_front)
            ->setData([
                'nav_id' => $nav_id,
                'alias' => $alias
            ])
            ->setHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->send();
        if ($response->isOk) {
            return $response->data;
        }

        return false;
    }
}