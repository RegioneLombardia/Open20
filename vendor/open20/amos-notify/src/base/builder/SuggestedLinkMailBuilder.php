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
class SuggestedLinkMailBuilder extends ContentMailBuilder
{
    /**
     * @return string
     */
    public function getSubject(array $resultset)
    {
        return Yii::t('amosnotify', "#Suggested_link_Subject");
    }
    
    
    /**
     * @param $resultSetNormal
     * @param $user
     * @return string
     */
    protected function renderEmailUserNotify($resultSetNormal, $user){
        $mail = '';
//        try {
            if (isset($resultSetNormal['community']) && count($resultSetNormal['community'])) {
                // ------------ USER IN COMMUNITY  -------------
                $mail .= $this->renderSectionTitle(Yii::t('amosnotify', "#SuggestedLink_community_title"), Yii::t('amosnotify', "#SuggestedLink_community_desc"));        

                $mail .= $this->renderSectionWithClasses($resultSetNormal['community'], $user);
            }
            
            if (isset($resultSetNormal['comments']) && count($resultSetNormal['comments'])) {
                // ------------ WHO WROTE COMMENTS  -------------
                $mail .= $this->renderSectionTitle(Yii::t('amosnotify', "#SuggestedLink_comments_title"), Yii::t('amosnotify', "#SuggestedLink_comments_desc"));        

                $mail .= $this->renderSectionWithClasses($resultSetNormal['comments'], $user);
            }
            
            if (isset($resultSetNormal['network']) && count($resultSetNormal['network'])) {
                // ------------ CONTACTS OF CONTACTS  -------------
                $mail .= $this->renderSectionTitle(Yii::t('amosnotify', "#SuggestedLink_network_title"), Yii::t('amosnotify', "#SuggestedLink_network_desc"));        

                $mail .= $this->renderSectionWithClasses($resultSetNormal['network'], $user);
            }
            
            if (isset($resultSetNormal['organizations']) && count($resultSetNormal['organizations'])) {
                // ------------ IN THE SAME ORGANIZZATIONS  -------------
                $mail .= $this->renderSectionTitle(Yii::t('amosnotify', "#SuggestedLink_organizations_title"), Yii::t('amosnotify', "#SuggestedLink_organizations_desc"));        

                $mail .= $this->renderSectionWithClasses($resultSetNormal['organizations'], $user);
            }
            

//        } catch (\Exception $ex) {
//            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
//        }

        return $mail;
    }
}