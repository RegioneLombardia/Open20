<?php
namespace common\modules\transformermanagers;

use open20\amos\news\models\News;
use open20\elasticsearch\transformer\AbstractTransformerManager;




class NewsTransformerManager extends AbstractTransformerManager
{

    public function init() {
        parent::init();
        $this->objectClass = News::className();
    }

    public function getElasticToModelTransformer()
    {
        return new NewsEtoMTransformer(['objectClass' => $this->getObjectClass()]);
    }

    public function getModelToElasticTransformer()
    {
        return new NewsMtoETransformer(['objectClass' => $this->getObjectClass()]);
    }

}
