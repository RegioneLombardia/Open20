<?php

namespace amos\cmsbridge\models;

use amos\cmsbridge\utility\CmsObject;

class CmsResultCreatePage extends CmsObject implements CmsResultPage
{
    public $status;
    public $nav_id;
    public $nav_id_tks_page;
    public $nav_id_wating_page;
    public $nav_id_already_present_page;
    public $preview_url;

    /**
     *
     * @param mixed $value
     */
    public function build($value)
    {
        $this->json_decode($value);
    }
}