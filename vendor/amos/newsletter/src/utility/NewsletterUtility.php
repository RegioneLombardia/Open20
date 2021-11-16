<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 21/01/2019
 * Time: 12:37
 */

namespace amos\newsletter\utility;


class NewsletterUtility
{
    /**
     * @return null
     */
    public static function getCurrentMailService(){
        $module = \Yii::$app->getModule('newsletter');
        $serviceMail = null;
        if($module){
            $DriverClass = $module->mail_service_driver;
            $serviceMail = new $DriverClass();
        }
        return $serviceMail;
    }

    /**
     * @return string
     */
    public static function getCurrentMailServiceName(){
        $name = '';
        $serviceMail = NewsletterUtility::getCurrentMailService();
        if($serviceMail){
            $name = $serviceMail->getMailServiceName();
        }
        return $name;
    }

    /**
     * @param $idList
     * @return array
     */
    public static function actionGetGroupsByList($idList)
    {
        $mailService = NewsletterUtility::getCurrentMailService();
        $groups = [];
        if ($mailService) {
            $decoded = $mailService->getGroupsByList($idList);
            if (!empty($decoded->Items)) {
                $groups = $decoded->Items;
            }
        }
        return $groups;

    }


}