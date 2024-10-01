<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 24/09/2021
 * Time: 10:41
 */

namespace amos\userauth\frontend\utility;


use yii\validators\IpValidator;

class CmsUserauthUtility
{
    /**
     * @return bool
     */
    public static function isAccessPermitted()
    {
        $ip = null;
        $allowed1  = false;
        $allowed2  = false;
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip1 =  $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if(!empty($_SERVER['REMOTE_ADDR'])){
            $ip2 =  $_SERVER['REMOTE_ADDR'];
        }


        if(!empty($ip1)){
            $allowedIPs = [];
            $module = \Yii::$app->getModule('admin');
            if($module){
                $allowedIPs = \Yii::$app->params['loginAllowedIPs'];
            }
            $ipValidator = new IpValidator(['ranges' => $allowedIPs]);
            $allowed1 = $ipValidator->validate($ip1);
        }

        if(!empty($ip2)){
            $allowedIPs = [];
            $module = \Yii::$app->getModule('admin');
            if($module){
                $allowedIPs = \Yii::$app->params['loginAllowedIPs'];
            }
            $ipValidator = new IpValidator(['ranges' => $allowedIPs]);
            $allowed2 = $ipValidator->validate($ip2);
        }

        return ($allowed1 || $allowed2);
    }


}