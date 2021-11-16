<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\base\builder
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\base\builder;

use open20\amos\notificationmanager\AmosNotify;

class ContentImmediateMailBuilder extends ContentMailBuilder
{

    /**
     * @param array $resultset
     * @return string
     */
    protected function renderContentHeader(array $resultset)
    {
        $controller = \Yii::$app->controller;
        $contents_number = count($resultset);

        $view = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_immediate_email_header", [
            'contents_number' => $contents_number
        ]);

        $ris = $this->renderView(\Yii::$app->controller->module->name, "content_immediate_email_header", [
            'contents_number' => $contents_number,
            'original' => $view
        ]);

        return $ris;
    }

}