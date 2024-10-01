<?php

namespace common\modules\transformermanagers;

use open20\elasticsearch\models\ElasticIndex;
use open20\elasticsearch\transformer\modeltransformers\BaseElasticToModelTRansformer;

class DiscussioniTopicEtoMTransformer extends BaseElasticToModelTRansformer {
    /* @var $model ElasticIndex */

    public function transform() {
        $values = $this->elasticObject['_source'];
        $values['primo_piano'] = $values['public'];
        unset($values['public']);
        unset($values['title']);
        unset($values['risposte']);
        unset($values['commenti']);
        unset($values['start_publication']);

        $class = $this->getObjectClass();
        $model = new $class($values);
        $model->id = $this->elasticObject['_id'];
        return $model;
    }

}
