<?php

namespace common\modules\transformermanagers;

use open20\amos\news\models\News;
use open20\amos\news\models\NewsCategorie;
use open20\elasticsearch\transformer\AbstractTransformer;
use open20\elasticsearch\transformer\modeltransformers\BaseModelToElasticTransformer;
use yii\helpers\Console;
use yii\helpers\VarDumper;

class NewsMtoETransformer extends BaseModelToElasticTransformer
{
    /* @var $model News */

    public function transform()
    {
        $model = $this->getModel();
        $category = $model->newsCategorie;
        if (is_null($category)) {
            $category = new NewsCategorie();
        }
        $values                          = [];
        $values['title']                 = $this->filterString($model->titolo);
        $values['titolo']                = $this->filterString($model->titolo);
        $values['sottotitolo']           = $this->filterString($model->sottotitolo);
        $values['descrizione']           = $this->filterString($model->descrizione);
        $values['descrizione_breve']     = $this->filterString($model->descrizione_breve);
        $values['categoria_titolo']      = $this->filterString($category->titolo);
        $values['categoria_descrizione'] = $this->filterString($category->descrizione);
        $values['public']                = ($model->primo_piano && $model->status == News::NEWS_WORKFLOW_STATUS_VALIDATO) ? 1 : 0;
        $values['tags']                  = $this->getTags($model);
        $values['start_publication'] =  date("c",strtotime($model->data_pubblicazione));
        $values['end_publication'] =  date("c",strtotime($model->data_rimozione));
        $content                         = $model->newsContentType;
        $values['content_type']          = (is_null($content) ? '' : "'".$content->name)."'";
        return $values;
    }

}