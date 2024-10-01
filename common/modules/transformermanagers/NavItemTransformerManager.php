<?php
namespace common\modules\transformermanagers;

use open20\elasticsearch\models\NavItem;
use open20\elasticsearch\transformer\AbstractTransformerManager;


class NavItemTransformerManager extends AbstractTransformerManager
{
    
    public function init() {
        parent::init();
        $this->objectClass = NavItem::className();
    }
    
    public function getElasticToModelTransformer() 
    {
        return new NavItemEtoMTransformer(['objectClass' => $this->getObjectClass()]);
    }

    public function getModelToElasticTransformer() 
    {
        return new NavItemMtoETransformer(['objectClass' => $this->getObjectClass()]);
    }
}
