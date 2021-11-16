<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\seo\behaviors
 * @category   CategoryName
 */

namespace open20\amos\seo\behaviors;

use open20\amos\seo\AmosSeo;
use open20\amos\seo\models\SeoData;

use Yii;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * Class SeoBehaviors
 * @package open20\amos\seo\behaviors
 */
class SeoBehaviors extends AttributeBehavior
{
    
    public $postParams;


    /**
     * @var \open20\amos\core\record\Record $sender ;
     */
    private $sender;
    
    /**
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'eventSaveSeoData',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'eventSaveSeoData',
            // non so se serve BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'eventBeforeValidate',
            BaseActiveRecord::EVENT_BEFORE_DELETE => 'eventBeforeDelete'
        ];
    }
    
    /**
     * @param Event $event
     */
    public function eventSaveSeoData(Event $event)
    {
        $this->setSender($event->sender);
        
        if (isset($this->getSender()->deleted_at)) {
            return;
        }
        $seoData = SeoData::findOne([
                    'classname' => $this->getSender()->className(),
                    'content_id' => $this->getSender()->id
        ]);
        
        if (is_null($seoData)) {
            $seoData = new SeoData();
        }
        //pr($seoData->toArray(), 'eventSaveSeoData - $seoData');//exit;
        $pars = Yii::$app->request->post();
  
        $post = [];
        
        if (isset($_POST['SeoData'])) {
            $this->postParams = $_POST['SeoData'];
        }
        //pr($this->postParams, '$this->postParams');exit;
        $seoData->aggiornaSeoData($this->getSender(), $this->postParams);
        
    }
    
    
    /**
     * @param Event $event
     */
    public function eventBeforeDelete(Event $event)
    {
        $this->setSender($event->sender);
                
        if (isset($this->getSender()->deleted_at)) {
            return;
        }
        $seoData = SeoData::findOne([
                    'classname' => $this->getSender()->className(),
                    'content_id' => $this->getSender()->id
        ]);
        if (!is_null($seoData)) {
            $seoData->delete();
        }
    }
    
    
    /**
     * @param \yii\db\ActiveRecord $sender
     */
    private function setSender($sender)
    {
        $this->sender = $sender;
    }
    
    /**
     * @return \open20\amos\core\record\Record
     */
    private function getSender()
    {
        return $this->sender;
    }
    
}
