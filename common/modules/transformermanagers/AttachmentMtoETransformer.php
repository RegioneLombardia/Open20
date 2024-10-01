<?php

namespace common\modules\transformermanagers;

use open20\amos\attachments\models\File;
use open20\elasticsearch\transformer\modeltransformers\BaseModelToElasticTransformer;

class AttachmentMtoETransformer extends BaseModelToElasticTransformer {
    /* @var $model File */

    public function transform() {
        $model = $this->getModel();

        $values = [];
        $values['title'] = $this->filterString($model->name);
        $values['titolo'] = $this->filterString($model->name);
        $values['type'] = $this->filterString($model->type);
        $values['public'] = 1;
        $values['start_publication'] = date("c", strtotime("-9999-01-01"));
        $values['end_publication'] = date("c", strtotime("9999-12-31"));

        return $values;
    }

}
