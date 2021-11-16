<?php
/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace amos\cmsbridge\models;

use amos\cmsbridge\utility\CmsObject;

/**
 * Description of CmsResultHtmlPage
 *
 */
class CmsResultHtmlPage extends CmsObject implements CmsResultPage
{
    public $status;
    public $html;

    /**
     *
     * @param mixed $value
     */
    public function build($value)
    {
        $this->html = $value;
    }
}