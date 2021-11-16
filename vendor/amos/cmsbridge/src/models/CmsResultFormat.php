<?php

namespace amos\cmsbridge\models;

class CmsResultFormat
{
    const JSON  = 'json';
    const JSONP = 'jsonp';
    const HTML  = 'html';

    /**
     *
     * @param mixed $type
     * @return CmsResultPage
     */
    public static function format($type): CmsResultPage
    {
        switch ($type) {
            case static::HTML :
                return new CmsResultHtmlPage();
                break;
            case static::JSON:
                return new CmsResultCreatePage();
                break;
            default :
                return new CmsResultCreatePage();
                break;
        }
    }
}