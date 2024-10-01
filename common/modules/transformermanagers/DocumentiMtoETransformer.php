<?php
namespace common\modules\transformermanagers;

use open20\amos\core\record\CachedActiveQuery;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiCategorie;
use open20\elasticsearch\transformer\modeltransformers\BaseModelToElasticTransformer;


class DocumentiMtoETransformer extends BaseModelToElasticTransformer
{
    /* @var $model Documenti */
    public function transform() 
    {
        $model = $this->getModel();
        $category = $model->documentiCategorie;
        if(is_null($category))
        {
            $category = new DocumentiCategorie();
        }
        $values = [];
        $values['titolo'] = $this->filterString($model->titolo);
        $values['title'] = $this->filterString($model->titolo);
        $values['sottotitolo'] = $this->filterString($model->sottotitolo);
        $values['descrizione'] = $this->filterString($model->descrizione);
        $values['descrizione_breve'] = $this->filterString($model->descrizione_breve);
        
        $values['categoria_titolo'] = $this->filterString($category->titolo);
        $values['categoria_sottotitolo'] = $this->filterString($category->sottotitolo);
        $values['categoria_descrizione'] = $this->filterString($category->descrizione);
        $values['categoria_descrizione_breve'] = $this->filterString($category->descrizione_breve);
        $values['start_publication'] =  date("c",strtotime($model->data_pubblicazione));
        $values['end_publication'] =  date("c",strtotime($model->data_rimozione));
        $values['public'] = $model->primo_piano;
        $values['tags'] = $this->getTags($model);
        $content = $model->documentiAgidContentType;
        $type = $model->documentiAgidType;
        $values['content_type'] = sprintf("'%s'",(is_null($content) ? '' : $content->name) . "','" . (is_null($type) ? '' : $type->name));
        return $values;
    }
    
    
}
