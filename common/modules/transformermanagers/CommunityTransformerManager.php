<?php
namespace common\modules\transformermanagers;

use open20\amos\community\models\Community;
use open20\elasticsearch\transformer\AbstractTransformerManager;


class CommunityTransformerManager extends AbstractTransformerManager
{
    public function init() {
        parent::init();
        $this->objectClass = Community::className();
    }
    
    public function getElasticToModelTransformer() 
    {
        return new CommunityEtoMTransformer(['objectClass' => $this->getObjectClass()]);
    }

    public function getModelToElasticTransformer() 
    {
        return new CommunityMtoETransformer(['objectClass' => $this->getObjectClass()]);
    }
}
