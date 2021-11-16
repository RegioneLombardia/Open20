<?php

namespace open20\amos\events\models;

use DateTime;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\base\EventLanding as BaseEventLanding;
use open20\amos\events\models\EventType;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_landing".
 */
class EventLanding extends BaseEventLanding
{

    const STREAMING_TYPE_YOUTUBE = 1;
    const STREAMING_TYPE_FACEBOOK = 2;
    const STREAMING_TYPE_MEDIAPORTAL = 3;


    /**
     *
     */
    public function init(){
        parent::init();

    }
    /**
     * @return array
     */
    public function getLabelTypeStreaming(){
        return [
            self::STREAMING_TYPE_YOUTUBE => AmosEvents::t('amosevents', 'Youtube'),
            self::STREAMING_TYPE_FACEBOOK => AmosEvents::t('amosevents', 'Facebook'),
            self::STREAMING_TYPE_MEDIAPORTAL => AmosEvents::t('amosevents', 'MediaPortal')
        ];
    }



    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'event_id',
                'label' => $labels['event_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
            [
                'slug' => 'schedule',
                'label' => $labels['schedule'],
                'type' => 'text'
            ],
            [
                'slug' => 'image_slider_id',
                'label' => $labels['image_slider_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'video_slider_id',
                'label' => $labels['video_slider_id'],
                'type' => 'integer'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    /**
     * @param $model Event
     * @param $form
     */
    public function createLandingPage($model)
    {
        $moduleEvents = \Yii::$app->getModule('events');
        $url_Image = '';
        if($moduleEvents){
            if(!empty($moduleEvents->default_image_patronage)){
                $url_Image = Yii::$app->params['platform']['backendUrl'].$moduleEvents->default_image_patronage;
            }
        }
        /** @var  $location EventLocation */
        $beginDate = null;
        $endDate = null;
        if ($model->begin_date_hour) {
            $beginDate = new \DateTime($model->begin_date_hour);
        }
        if ($model->end_date_hour) {
            $endDate = new \DateTime($model->end_date_hour);
        }
        $location = $model->eventLocation;
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
        $page->alias = $this->url; // alias pagina
        $type = $model->eventType;
        if (!is_null($type) && $type->event_type == EventType::TYPE_UPON_INVITATION) {
            $page->with_login = 1;
        } else {
            $page->with_login = 0;
        }

        if($endDate && $beginDate){
            $dateString = AmosEvents::t('amosevents','dal ').$beginDate->format('d/m/Y H:i').' al '.$endDate->format('d/m/Y H:i');
        }
        else{
            $dateString = $beginDate ? $beginDate->format('d/m/Y H:i') : '';
        }
        $page->event_data->event_id = $model->id; // id dell'evento
        $page->event_data->title = $model->title;
        $page->event_data->event_date = $dateString;
        $page->event_data->presentation = $this->description;
        $page->event_data->program = $this->schedule;
        $page->event_data->description = $model->description;

        if(!empty( $model->enter_time)) {
            $enterTime = new \DateTime($model->enter_time);
            $enterTime->format('H:i');
            $page->event_data->enter_time = "ingresso dalle " . $enterTime->format('H:i');
        }

        if($model->eventType->patronage){
            $page->event_data->url_image = $url_Image;
        }
        else if (!empty($model->eventLogo)) {
            $page->event_data->url_image = Yii::$app->params['platform']['backendUrl'] . $model->eventLogo->getWebUrl(); //Web Url
        }

        $maxDate = new \DateTime('2038-01-19 04:14:00');
        if ($model->status != Event::EVENTS_WORKFLOW_STATUS_PUBLISHED && $maxDate) {
            $page->event_data->opening_date = $maxDate->format('Y-m-d H:i:s');
        } else {
            $page->event_data->opening_date = (new \DateTime($model->publication_date_begin))->format('Y-m-d H:i:s');
        }

        $page->form_landing->social_reg = $this->social_reg;
        $page->form_landing->facebook_reg = $this->facebook_reg;
        $page->form_landing->twitter_reg = $this->twitter_reg;
        $page->form_landing->linkedin_reg = $this->linkedin_reg;
        $page->form_landing->goolge_reg = $this->goolge_reg;
        $page->form_landing->goolge_reg = $this->apple_reg;
        $page->form_landing->spid_cns_reg = $this->spid_cns_reg;

        $page->form_landing->user_name_reg = $this->user_name_reg;
        if ($this->user_name_reg) {
            $page->form_landing->ask_sex = $this->ask_sex;
            $page->form_landing->ask_age = $this->ask_age;
            $page->form_landing->ask_county = $this->ask_county;
            $page->form_landing->ask_city = $this->ask_city;
            $page->form_landing->ask_telefon = $this->ask_telefon;
            $page->form_landing->ask_fiscal_code = $this->ask_fiscal_code;
            $page->form_landing->ask_company = $this->ask_company;
            $page->form_landing->ask_tags = $this->ask_tags;

            $page->form_landing->ask_sex_required = $this->ask_sex_required;
            $page->form_landing->ask_age_required = $this->ask_age_required;
            $page->form_landing->ask_county_required = $this->ask_county_required;
            $page->form_landing->ask_city_required = $this->ask_city_required;
            $page->form_landing->ask_telefon_required = $this->ask_telefon_required;
            $page->form_landing->ask_fiscal_code_required = $this->ask_fiscal_code_required;
            $page->form_landing->ask_company_required = $this->ask_company_required;
            $page->form_landing->ask_tags_required = $this->ask_tags_required;
        }
        $page->form_landing->community_id = $model->community_id;
        $page->form_landing->seats_available = $model->seats_available;

        /** var $templates EventEmailTemplates*/
        $templates = $model->eventEmailTemplates;
        if (empty($templates)) {
            $page->form_landing->confirm_mail_subject = $templates->confirm_registration_subject;
            $page->form_landing->confirm_mail_text = $templates->confirm_registration;
            $page->form_landing->waiting_mail_subject = $templates->info_waiting_list_subject;
            $page->form_landing->waiting_mail_text = $templates->info_waiting_list;
        }

        /** @var  $place EventPlaces */
        $place = $location->eventPlaces;
        if ($place) {
            $page->event_data->location->location_name = $location->name;
            $page->event_data->location->location_description = $location->description;
            $page->event_data->location->location_address = $model->getCompleteLocationString();
            $page->event_data->location->place_response = $place->place_response;
            $page->event_data->location->place_type = $place->place_type;
            $page->event_data->location->country = $place->country;
            $page->event_data->location->region = $place->region;
            $page->event_data->location->province = $place->province;
            $page->event_data->location->postal_code = $place->postal_code;
            $page->event_data->location->city = $place->city;
            $page->event_data->location->address = $place->address;
            $page->event_data->location->street_number = $place->street_number;
            $page->event_data->location->latitude = $place->latitude;
            $page->event_data->location->longitude = $place->longitude;
        }

        $page->form_landing->tks_you_page_message = $this->thank_you_subscribe;
        $page->form_landing->waiting_page_message = $this->thank_you_waiting_list;
        $page->form_landing->already_present_page_message = $this->thank_you_registered;

        $page->cms_user_id = $utility->loginCms();
        if (!empty($this->luya_page_id)) {
            $page->nav_id = $this->luya_page_id;
            $page->form_landing->nav_id_tks_page = $this->nav_id_tks_page;
            $page->form_landing->nav_id_wating_page = $this->nav_id_wating_page;
            $page->form_landing->nav_id_already_present_page = $this->nav_id_already_present_page;
            $result = $utility->updateCmsPage($page);
        } else {
            $result = $utility->createCmsPage($page);
            if (!empty($result->status) && $result->status == 1) {
                $this->luya_page_id = $result->nav_id;
                $this->nav_id_tks_page = $result->nav_id_tks_page;
                $this->nav_id_wating_page = $result->nav_id_wating_page;
                $this->nav_id_already_present_page = $result->nav_id_already_present_page;
                $this->save(false);
            }
        }
    }

    /**
     * @return mixed|string
     */
    public function getUrlMediaPortalFormatted(){
        $url = $this->streaming_url;
        if($this->streaming_type == self::STREAMING_TYPE_MEDIAPORTAL) {
            if(strpos($this->streaming_url, '/portal/watch/live/') >= 0){
                $url = str_replace('/portal/watch/live/', '/embed/live/', $url);
            }
        }
        return $url;
    }


//        pr($page);
//        pr($result);die;


}
