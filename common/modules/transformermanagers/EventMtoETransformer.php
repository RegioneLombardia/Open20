<?php
namespace common\modules\transformermanagers;

use open20\amos\events\models\Event;
use open20\elasticsearch\transformer\modeltransformers\BaseModelToElasticTransformer;

class EventMtoETransformer extends BaseModelToElasticTransformer 
{
    /* @var $model Event */
    public function transform() 
    {
        $model = $this->getModel();
        $values = [];
        $values['title'] = $this->filterString($model->title);
        $values['summary'] = $this->filterString($model->summary);
        $values['description'] = $this->filterString($model->description);
        $values['begin_date_hour'] = $this->filterString($model->begin_date_hour);
        $values['public'] = $model->primo_piano;
        $values['tags'] = $this->getTags($model);
        $values['start_publication'] =  date("c",strtotime($model->publication_date_begin));
        $values['end_publication'] =  date("c",strtotime($model->publication_date_end));
        
        return $values;
    }
    
}
