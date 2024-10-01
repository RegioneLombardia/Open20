<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\models
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\models;

use open20\amos\organizzazioni\Module;

/**
 * Class ProfiloUserMm
 * This is the model class for table "profilo_user_mm".
 * @package open20\amos\organizzazioni\models
 */
class ProfiloUserMm extends \open20\amos\organizzazioni\models\base\ProfiloUserMm
{
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_WAITING_REQUEST_CONFIRM = 'WAITING_REQUEST_CONFIRM';
    const STATUS_WAITING_OK_ORGANIZATION_REFEREE = 'REQUEST_SENT';
    const STATUS_WAITING_OK_USER = 'INVITED';
    const STATUS_INVITE_IN_PROGRESS = 'INVITING';

    /**
     * Return all statuses of the organization users.
     * @return array
     */
    public static function getUserStates()
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_REJECTED,
            self::STATUS_WAITING_REQUEST_CONFIRM,
            self::STATUS_WAITING_OK_ORGANIZATION_REFEREE,
            self::STATUS_WAITING_OK_USER,
            self::STATUS_INVITE_IN_PROGRESS
        ];
    }

    /**
     * @inheritdoc
     */
    public function getModelModuleName()
    {
        return Module::getModuleName();
    }

    /**
     * @inheritdoc
     */
    public function getModelControllerName()
    {
        return 'profilo';
    }
}
