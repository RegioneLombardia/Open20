<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\models
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\models;


use yii\base\BaseObject;

/**
 * Class ChangeStatusEmail
 *
 * Model used to create a custom email on change workflow status
 *
 * @package open20\amos\notificationmanager\models
 */
class ChangeStatusEmail extends BaseObject
{
    /**
     * @var string $startStatus
     */
    public $startStatus;

    /**
     * @var string $template
     */
    public $template;

    /**
     * @var string $subject
     */
    public $subject;

    /**
     * @var array $params
     */
    public $params = [];

    /**
     * @var bool|false $toCreator
     */
    public $toCreator = false;

    /**
     * @var bool|true $toValidator
     */
    public $toValidator = true;

    /**
     * @var array $recipientUserIds
     */
    public $recipientUserIds = [];

}