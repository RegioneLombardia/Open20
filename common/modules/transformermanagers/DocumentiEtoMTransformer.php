<?php
namespace common\modules\transformermanagers;

use open20\elasticsearch\transformer\modeltransformers\BaseElasticToModelTRansformer;

class DocumentiEtoMTransformer extends BaseElasticToModelTRansformer
{
    /* @var $model ElasticIndex */
    public function transform() 
    {
        $values = $this->elasticObject['_source'];
        $values['primo_piano'] = $values['public'];
        unset($values['public']);
        unset($values['categoria_titolo']);
        unset($values['categoria_sottotitolo']);
        unset($values['categoria_descrizione']);
        unset($values['categoria_descrizione_breve']);
        unset($values['content_type']);
        unset($values['title']);
        unset($values['start_publication']);
        unset($values['end_publication']);
        $class = $this->getObjectClass();
        $model = new $class($values);
        $model->id = $this->elasticObject['_id'];
        return $model;
    }
}
