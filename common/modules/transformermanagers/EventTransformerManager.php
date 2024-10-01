<?php
namespace common\modules\transformermanagers;

use open20\amos\events\models\Event;
use open20\elasticsearch\transformer\AbstractTransformerManager;


class EventTransformerManager extends AbstractTransformerManager
{
    public function init() {
        parent::init();
        $this->objectClass = Event::className();
    }
    
    public function getElasticToModelTransformer() 
    {
        return new EventEtoMTransformer(['objectClass' => $this->getObjectClass()]);
    }

    public function getModelToElasticTransformer() 
    {
        return new EventMtoETransformer(['objectClass' => $this->getObjectClass()]);
    }
}
