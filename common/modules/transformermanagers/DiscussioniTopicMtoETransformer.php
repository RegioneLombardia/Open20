<?php

namespace common\modules\transformermanagers;

use open20\amos\core\record\CachedActiveQuery;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\elasticsearch\transformer\modeltransformers\BaseModelToElasticTransformer;

class DiscussioniTopicMtoETransformer extends BaseModelToElasticTransformer {
    /* @var $model DiscussioniTopic */

    public function transform() {
        
        $model = $this->getModel();
        $values = [];
        $values['title'] = $this->filterString($model->titolo);
        $values['titolo'] = $this->filterString($model->titolo);
        $values['testo'] = $this->filterString($model->testo);
        $values['public'] = $model->primo_piano;
        $values['tags'] = $this->getTags($model);
        $values['risposte'] = "";
        $values['commenti'] = "";
        $values['start_publication'] = date("c", strtotime($model->created_at));

        $risposte = $model->discussioniRisposte;
        foreach ($risposte as $risposta) {
            $values['risposte'] .= " " . $this->filterString($risposta->testo);
            $commenti = $risposta->discussioniCommentis;
            foreach ($commenti as $commento) {
                $values['risposte'] .= " " . $this->filterString($commento->testo);
            }
        }
        return $values;
    }

}
