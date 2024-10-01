<?php

namespace common\modules\transformermanagers;

use open20\elasticsearch\models\ElasticIndex;
use open20\elasticsearch\transformer\AbstractTransformer;
use open20\elasticsearch\transformer\modeltransformers\BaseElasticToModelTRansformer;
use yii\helpers\Console;

class NavItemEtoMTransformer extends BaseElasticToModelTRansformer {
    /* @var $model ElasticIndex */

    public function transform() {
        $values = $this->elasticObject['_source'];
        unset($values['public']);
        unset($values['start_publication']);
        unset($values['end_publication']);
        unset($values[AbstractTransformer::STATUS_INDEX]);
        unset($values['elasticSourceText']);
        unset($values['elasticUrl']);
        $class = $this->getObjectClass();
        $model = new $class($values);
        $model->id = $this->elasticObject['_id'];
        return $model;
    }

}
