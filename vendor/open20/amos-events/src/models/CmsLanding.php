<?php

namespace open20\amos\events\models;


use yii\base\Model;

class CmsLanding extends Model
{
    public $luya_template_id;

    public $social_reg;
    public $social_accounts = [];
    public $facebook_reg;
    public $twitter_reg;
    public $linkedin_reg;
    public $goolge_reg;
    public $spid_cns_reg;
    public $user_name_reg;


    public $userData = [];
    public $ask_sex;
    public $ask_age;
    public $ask_county;
    public $ask_city;
    public $ask_telefon;
    public $ask_fiscal_code;
    public $ask_company;
    public $ask_tags;

    public $url;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'url',
                    'luya_template_id',
                    'userData',
                    'social_reg',
                    'facebook_reg',
                    'twitter_reg',
                    'linkedin_reg',
                    'goolge_reg',
                    'spid_cns_reg',
                    'user_name_reg',
                    'ask_sex',
                    'ask_age',
                    'ask_county',
                    'ask_city',
                    'ask_telefon',
                    'ask_fiscal_code',
                    'ask_company',
                    'ask_tags',
                ]
                , 'safe'
            ]
        ];
    }

    /**
     * @param $model Event
     * @param $form
     */
    public function createLandingPage($model){
        $utility = new \amos\cmsbridge\utility\CmsUtility();
        $page = new \amos\cmsbridge\models\PostCmsCreatePage();
        $page->nav_item_type = 1;
        $page->parent_nav_id = 0;
        $page->is_draft = 0;
        $page->nav_container_id = 1;
        $page->lang_id = 1;
        $page->use_draft = 1;
        $page->layout_id = 0;
        $page->from_draft_id = $this->luya_template_id;
        $page->title = $model->title; // Titolo pagina
        $page->alias = "events-top-" . $model->id; // alias pagina

        $page->event_data->event_id = $model->id; // id dell'evento
        $page->event_data->presentation = $model->description;
        $page->event_data->description = $model->description;
        $page->event_data->program = $model->description;
        $page->event_data->url_image = $model->eventLogo->getWebUrl(); //Web Url


        $page->form_landing->social_reg = $this->social_reg;
        $page->form_landing->facebook_reg = $this->facebook_reg;
        $page->form_landing->twitter_reg = $this->twitter_reg;
        $page->form_landing->linkedin_reg = $this->linkedin_reg;
        $page->form_landing->goolge_reg = $this->goolge_reg;
        $page->form_landing->spid_cns_reg = $this->spid_cns_reg;
        $page->form_landing->user_name_reg = $this->user_name_reg;
        $page->form_landing->ask_sex = $this->ask_sex;
        $page->form_landing->ask_age = $this->ask_age;
        $page->form_landing->ask_county = $this->ask_county;
        $page->form_landing->ask_city = $this->ask_city;
        $page->form_landing->ask_telefon = $this->ask_telefon;
        $page->form_landing->ask_fiscal_code = $this->ask_fiscal_code;
        $page->form_landing->ask_company = $this->ask_company;
        $page->form_landing->ask_tags = $this->ask_tags;

        $user_id = $utility->loginCms();
        $page->cms_user_id = $user_id;
//        pr($page);

        $result = $utility->createCmsPage($page);
//        pr($result);die;
    }

}