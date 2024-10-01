<?php

namespace common\modules\transformermanagers;

use open20\amos\attachments\models\File;
use open20\elasticsearch\transformer\AbstractTransformerManager;

class AttachmentTransformerManager extends AbstractTransformerManager {

    public function init() {
        parent::init();
        $this->objectClass = File::className();
    }

    public function getElasticToModelTransformer() {
        return new AttachmentEtoMTransformer(['objectClass' => $this->getObjectClass()]);
    }

    public function getModelToElasticTransformer() {
        return new AttachmentMtoETransformer(['objectClass' => $this->getObjectClass()]);
    }

}
