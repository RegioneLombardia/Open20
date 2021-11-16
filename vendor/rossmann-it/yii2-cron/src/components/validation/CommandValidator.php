<?php
/**
 * @since 15.08.2016
 */

namespace rossmann\cron\components\validation;


use common\components\db\CustomActiveRecord;
use common\models\Campaigns\CampaignStatuses;
use common\models\Campaigns\CampaignTypes;
use common\models\Coupons\CouponTemplates;
use common\models\LegalTexts\LegalContainers;
use rossmann\cron\components\TaskManager;
use Yii;
use yii\validators\Validator;

class CommandValidator extends Validator {

    /**
     * @param CustomActiveRecord $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute) {
        if ($command = TaskManager::validateCommand($model->$attribute)) {
            $model->$attribute = $command;
        } else {
            $this->addError($model, $attribute, Yii::t('rbac-admin', 'invalid_command'));
        }
    }
}
