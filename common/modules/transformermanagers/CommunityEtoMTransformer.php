<?php

namespace common\modules\transformermanagers;

use open20\elasticsearch\models\ElasticIndex;
use open20\elasticsearch\transformer\modeltransformers\BaseElasticToModelTRansformer;

class CommunityEtoMTransformer extends BaseElasticToModelTRansformer {
    /* @var $model ElasticIndex */

    public function transform() {
        $values = $this->elasticObject['_source'];
        unset($values['public']);
        unset($values['tags']);
        unset($values['start_publication']);

        $class = $this->getObjectClass();
        $model = new $class($values);
        $model->id = $this->elasticObject['_id'];
        return $model;
    }

}
