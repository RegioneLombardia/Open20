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
use Yii;

/**
 * Class SleepingUserMailBuilder
 * @package open20\amos\notificationmanager\base\builder
 */
class SuccessfulUserMailBuilder extends SuccessfulContentMailBuilder
{
    /**
     * @return string
     */
    public function getSubject(array $resultset)
    {
        return Yii::t('amosnotify', "#Successful_User_Subject");
    }
    
    /**
     * @param $resultSetNormal
     * @param $user
     * @return string
     */
    protected function renderEmailUserNotify($resultSetNormal, $user){
        $mail = '';
//        try {
            if (isset($resultSetNormal['view_profile']) && count($resultSetNormal['view_profile'])) {
                
                $frequencyMsg = AmosNotify::t('amosnotify', $this->getFrequencyMessage());
                $viewMsg =  AmosNotify::t('amosnotify', '#Visto da ##n## persone', [$resultSetNormal['view_profile_howmany']]);
                $howManyMsg =  AmosNotify::t('amosnotify', '#Profilo ##visto## ##frequenza##', [$viewMsg, $frequencyMsg]);
                
                $mail .= $this->renderSectionTitle(Yii::t('amosnotify', "#SuggestedLink_view_profile_quanti_title"), $howManyMsg);
                
                // ------------ HANNO VISTO IL SUO PROFILO  -------------
                $mail .= $this->renderSectionTitle(Yii::t('amosnotify', "#SuggestedLink_view_profile_title"), Yii::t('amosnotify', "#SuggestedLink_view_profile_desc"));        

                $mail .= $this->renderSectionWithClasses($resultSetNormal['view_profile'], $user);
            }
                        

//        } catch (\Exception $ex) {
//            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
//        }

        return $mail;
    }
        
}