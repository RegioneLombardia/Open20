<?php

namespace common\modules\transformermanagers;

use open20\amos\admin\models\UserProfile;
use open20\elasticsearch\transformer\AbstractTransformerManager;

class UserTransformerManager extends AbstractTransformerManager {

    public function init() {
        parent::init();
        $this->objectClass = UserProfile::className();
    }

    public function getElasticToModelTransformer() {
        return new UserEtoMTransformer(['objectClass' => $this->getObjectClass()]);
    }

    public function getModelToElasticTransformer() {
        return new UserMtoETransformer(['objectClass' => $this->getObjectClass()]);
    }

}
