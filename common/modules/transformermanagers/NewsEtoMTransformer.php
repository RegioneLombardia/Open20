<?php

namespace common\modules\transformermanagers;

use open20\elasticsearch\transformer\AbstractTransformer;
use open20\elasticsearch\transformer\modeltransformers\BaseElasticToModelTRansformer;

class NewsEtoMTransformer extends BaseElasticToModelTRansformer {
    /* @var $model ElasticIndex */

    public function transform() {
        $values = $this->elasticObject['_source'];
        $values['primo_piano'] = $values['public'];
        unset($values['public']);
        unset($values['title']);
        unset($values['categoria_titolo']);
        unset($values['categoria_descrizione']);
        unset($values['content_type']);
        unset($values['start_publication']);
        unset($values['end_publication']);
        unset($values['tags']);
        $class = $this->getObjectClass();
        $model = new $class($values);
        $model->id = $this->elasticObject['_id'];
        return $model;
    }

}
