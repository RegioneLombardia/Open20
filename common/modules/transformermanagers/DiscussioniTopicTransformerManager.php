<?php
namespace common\modules\transformermanagers;

use open20\amos\discussioni\models\DiscussioniTopic;
use open20\elasticsearch\transformer\AbstractTransformerManager;


class DiscussioniTopicTransformerManager extends AbstractTransformerManager
{
    public function init() {
        parent::init();
        $this->objectClass = DiscussioniTopic::className();
    }
    
    public function getElasticToModelTransformer() 
    {
        return new DiscussioniTopicEtoMTransformer(['objectClass' => $this->getObjectClass()]);
    }

    public function getModelToElasticTransformer() 
    {
        return new DiscussioniTopicMtoETransformer(['objectClass' => $this->getObjectClass()]);
    }
}
