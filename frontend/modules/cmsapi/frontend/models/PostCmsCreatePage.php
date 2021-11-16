<?php

namespace app\modules\cmsapi\frontend\models;

use app\modules\cmsapi\frontend\models\PostCmsEventData;
use yii\base\Model;

class PostCmsCreatePage extends Model
{
    public $nav_id;
    public $use_draft;
    public $parent_nav_id;
    public $nav_container_id;
    public $title;
    public $description;
    public $is_draft;
    public $layout_id;
    public $alias;
    public $lang_id;
    public $from_draft_id;
    public $nav_item_type;
    public $cms_user_id;
    public $with_login = 0;
    private $form_landing = null;
    private $event_data   = null;

    public function getEvent_data(): PostCmsEventData
    {
        return $this->event_data;
    }

    public function setEvent_data(string $event_data)
    {
        $this->event_data->json_decode($event_data);
    }

    public function setEvent_dataObj(PostCmsEventData $event_data)
    {
        $this->event_data = $event_data;
    }

    /**
     *
     * @return PostCmsFormLanding
     */
    public function getForm_landing(): PostCmsFormLanding
    {
        return $this->form_landing;
    }

    /**
     *
     * @param string $form_landing
     */
    public function setForm_landing(string $form_landing)
    {
        $this->form_landing->json_decode($form_landing);
    }

    /**
     *
     * @param PostCmsFormLanding $form_landing
     */
    public function setForm_landingObj(PostCmsFormLanding $form_landing)
    {
        $this->form_landing = $form_landing;
    }

    /**
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->form_landing = new PostCmsFormLanding();
        $this->event_data   = new PostCmsEventData();
    }

    public function rules()
    {
        return [
            [['nav_id','use_draft', 'parent_nav_id', 'nav_container_id', 'is_draft', 'layout_id',
                'lang_id', 'from_draft_id', 'nav_item_type', 'cms_user_id', 'with_login'],
                'integer'],
            [['title', 'description', 'alias'], 'string'],
            [[ 'form_landing', 'event_data'], 'safe'],
        ];
    }
}