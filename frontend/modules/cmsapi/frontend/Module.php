<?php

namespace app\modules\cmsapi\frontend;

use luya\base\CoreModuleInterface;

final class Module extends \luya\base\Module implements CoreModuleInterface
{
    public $enableTagParsing;
    public $overlayToolbar;
    private $css_layoutsection_with_image = '';
    public $urlRules                      = [
        ['pattern' => 'api/1/qrcode', 'route' => 'cmsapi/cmsapi/qrcode'],
        ['pattern' => 'api/1/export-download', 'route' => 'cmsapi/cmsapi/export-download'],
        ['pattern' => 'api/1/news', 'route' => 'cmsapi/cmsapi/news'],
        ['pattern' => 'api/1/community', 'route' => 'cmsapi/cmsapi/community'],
        ['pattern' => 'api/1/redazione', 'route' => 'cmsapi/cmsapi/redazione'],
        ['pattern' => 'api/1/send-mail-after-login', 'route' => 'cmsapi/cmsapi/send-mail-after-login'],
        ['pattern' => 'api/1/create-page', 'route' => 'cmsapi/cmsapi/create-page'],
        ['pattern' => 'api/1/base-create-page', 'route' => 'cmsapi/cmsapi/base-create-page'],
        ['pattern' => 'api/1/list-templates', 'route' => 'cmsapi/cmsapi/list-templates'],
        ['pattern' => 'api/1/list-languages', 'route' => 'cmsapi/cmsapi/list-languages'],
        ['pattern' => 'api/1/update-page', 'route' => 'cmsapi/cmsapi/update-page'],
        ['pattern' => 'api/1/preview-page', 'route' => 'cmsapi/cmsapi/preview-page'],
        ['pattern' => 'api/1/preview-page-html', 'route' => 'cmsapi/cmsapi/get-preview-page-html'],
        ['pattern' => 'api/1/preview-content', 'route' => 'cmsapi/preview/preview-content'],
    ];

    public $contentXPathSelector = "//*[contains(@class, 'wrap-modules')]";
    public $contentXPathsToIgnore = [
        "//nav",
        "//form",
        "//*[contains(@class, 'modal')]",
        "//*[contains(@class, 'social-share')]",
    ];

    public function getTks_template_page_id()
    {
        return $this->tks_template_page_id;
    }

    public function getWaiting_template_page_id()
    {
        return $this->waiting_template_page_id;
    }

    public function getAlready_present_template_page_id()
    {
        return $this->already_present_template_page_id;
    }

    public function setTks_template_page_id($tks_template_page_id)
    {
        $this->tks_template_page_id = $tks_template_page_id;
    }

    public function setWaiting_template_page_id($waiting_template_page_id)
    {
        $this->waiting_template_page_id = $waiting_template_page_id;
    }

    public function setAlready_present_template_page_id($already_present_template_page_id)
    {
        $this->already_present_template_page_id = $already_present_template_page_id;
    }

    public function getCss_layoutsection_with_image()
    {
        return $this->css_layoutsection_with_image;
    }

    public function setCss_layoutsection_with_image($css_layoutsection_with_image)
    {
        $this->css_layoutsection_with_image = $css_layoutsection_with_image;
    }
}