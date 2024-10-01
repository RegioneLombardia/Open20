<?php

namespace app\modules\cmsapi\frontend\models;

use app\modules\cmsapi\frontend\utility\CmsObject;

class PostCmsEventData extends CmsObject
{
    public $event_id;
    public $opening_date;
    public $event_date;
    public $title;
    public $presentation;
    public $description;
    public $program;
    public $url_image;
    public $location;

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->location = new PostCmsEventLocation();
    }
}