<?php
namespace common\modules\transformermanagers;

use open20\amos\documenti\models\Documenti;
use open20\elasticsearch\transformer\AbstractTransformerManager;


class DocumentiTransformerManager extends AbstractTransformerManager
{
    public function init() {
        parent::init();
        $this->objectClass = Documenti::className();
    }
    
    public function getElasticToModelTransformer() 
    {
        return new DocumentiEtoMTransformer(['objectClass' => $this->getObjectClass()]);
    }

    public function getModelToElasticTransformer() 
    {
        return new DocumentiMtoETransformer(['objectClass' => $this->getObjectClass()]);
    }
}
