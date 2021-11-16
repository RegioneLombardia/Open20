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
use open20\amos\notificationmanager\models\NotificationsConfOpt;
use Yii;

/**
 * Class SleepingUserMailBuilder
 * @package open20\amos\notificationmanager\base\builder
 */
class SuccessfulContentMailBuilder extends ContentMailBuilder
{
    
    protected $idsCounts;
    protected $idsCountsGlobal;
    protected $frequency;

    /**
     * @return string
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @return string
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    /**
     * @return string
     */
    public function getSubject(array $resultset)
    {
        return Yii::t('amosnotify', "#Successful_Content_Subject");
    }

    /**
     * @param $resultSetNormal
     * @param $user
     * @return string
     */
    protected function renderEmailUserNotify($resultSetNormal, $user){
        $mail = '';
        $this->idsCounts = $resultSetNormal['idsCounts'];
        $this->idsCountsGlobal = $resultSetNormal['idsCountsGlobal'];
        
        
//        try {
                   
            if (isset($resultSetNormal['base']) && count($resultSetNormal['base'])) {
                // ------------ NOTIFICATION SECTION READED  -------------
                $mail .= $this->renderSectionTitle('', Yii::t('amosnotify', "#Successful_Content_Why_Mail")); 
                
                $mail .= $this->renderSectionWithClasses($resultSetNormal['base'], $user);
            }
                                    
            $mail .= $this->renderContentFooter($resultSetNormal, $user);

//        } catch (\Exception $ex) {
//            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
//        }

        return $mail;
    }
    
    protected function renderTextBeforeContent($classname) {
       
        //pr($this->idsCounts[$classname], 'idsCounts: '.$classname);
        //pr($this->idsCountsGlobal[$classname], 'idsCountsGlobal: '.$classname);die();
       
        $message = "id: ".$this->idsCountsGlobal[$classname].
                "; read: " . $this->idsCounts[$classname][$this->idsCountsGlobal[$classname]]['read']
                ."; comments: " . $this->idsCounts[$classname][$this->idsCountsGlobal[$classname]]['comments']
                ."; likes: " . $this->idsCounts[$classname][$this->idsCountsGlobal[$classname]]['likes'];
        
        if ($this->idsCounts[$classname][$this->idsCountsGlobal[$classname]]['read']) {
            $readMsg .=  AmosNotify::t('amosnotify', '#Letto da ##n## persone', [$this->idsCounts[$classname][$this->idsCountsGlobal[$classname]]['read']]);
        }
        if ($this->idsCounts[$classname][$this->idsCountsGlobal[$classname]]['likes']) {
            $likeMsg .=  AmosNotify::t('amosnotify', '#Like da ##n## persone', [$this->idsCounts[$classname][$this->idsCountsGlobal[$classname]]['likes']]);
        }
        if ($this->idsCounts[$classname][$this->idsCountsGlobal[$classname]]['comments']) {
            $commentsMsg .=  AmosNotify::t('amosnotify', '#Commentato da ##n## persone', [$this->idsCounts[$classname][$this->idsCountsGlobal[$classname]]['comments']]);
        }
        $frequencyMsg = AmosNotify::t('amosnotify', $this->getFrequencyMessage());
        $message = AmosNotify::t('amosnotify', '#Contenuto ##read## ##like## ##comments## ##frequenza##', [$readMsg, $likeMsg, $commentsMsg, $frequencyMsg]);


        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_howmany", [
            'message' => $message
        ]);
        return $ris;

    } // renderTextBeforeContent
    
    
    /**
     * @param Record[] $model
     * @param User $user
     * @return string
     */
    protected function getFrequencyMessage($frequency)
    {
        if(is_null($frequency)) {
            $frequency = $this->frequency;
        }
        
        $msg = '';
        if($frequency == NotificationsConfOpt::EMAIL_MONTH) {
            $msg = "#in_ultimi_30_giorni";
        } elseif($frequency == NotificationsConfOpt::EMAIL_WEEK) {
            $msg = "#in_ultimi_7_giorni";
        } else {
            $msg = "#in_ultimo_giorno";
        }
        return $msg;
    }
    
    
}