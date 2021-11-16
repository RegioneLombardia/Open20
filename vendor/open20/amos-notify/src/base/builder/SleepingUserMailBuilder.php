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

use Yii;

/**
 * Class SleepingUserMailBuilder
 * @package open20\amos\notificationmanager\base\builder
 */
class SleepingUserMailBuilder extends ContentMailBuilder
{
    /**
     * @return string
     */
    public function getSubject(array $resultset)
    {
        return Yii::t('amosnotify', "#Sleeping_User_Subject");
    }
    
    /**
     * @param $resultSetNormal
     * @param $user
     * @return string
     */
    public function renderSectionWithClasses($resultSetNormal, $user){

        $mail = '';
        
        $mail .= $this->renderSectionTitle('', Yii::t('amosnotify', "#Sleeping_User_Why_Mail")); 
        
        $mail .= parent::renderSectionWithClasses($resultSetNormal, $user);        
        
        return $mail;
        
    }    
}