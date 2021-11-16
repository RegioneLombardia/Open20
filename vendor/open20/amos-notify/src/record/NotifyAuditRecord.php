<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\record;


use \open20\amos\audit\AuditTrailBehavior;
use \yii\helpers\ArrayHelper;

class NotifyAuditRecord extends NotifyRecord implements NotifyRecordInterface
{

    /**
     * @return mixed
     */
    public function behaviors()
    {

        $behaviorsParent = parent::behaviors();

        $behaviors = [
            'auditTrailBehavior' => [
                'class' => AuditTrailBehavior::className()
            ],
        ];

        return ArrayHelper::merge($behaviorsParent, $behaviors);
    }
}