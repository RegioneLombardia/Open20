<?php

namespace common\modules\transformermanagers;

use open20\elasticsearch\models\ElasticIndex;
use open20\elasticsearch\transformer\modeltransformers\BaseElasticToModelTRansformer;

class UserEtoMTransformer extends BaseElasticToModelTRansformer {
    /* @var $model ElasticIndex */

    public function transform() {
        $values = $this->elasticObject['_source'];
        unset($values['public']);
        unset($values['title']);
        unset($values['titolo']);
        unset($values['start_publication']);
        unset($values['end_publication']);
        unset($values['email']);
        unset($values['numeroMatricola']);
        unset($values['descrizione']);
        $class = $this->getObjectClass();
        $model = new $class($values);
        $model->id = $this->elasticObject['_id'];
        return $model;
    }

}
