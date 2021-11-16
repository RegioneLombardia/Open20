<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    e015\common\events
 * @category   CategoryName
 */

namespace open20\amos\privileges\events;

use yii\base\Event;

/**
 * Class PrivilegesEvent
 * @package open20\amos\privileges\events
 */
class PrivilegesEvent extends Event
{
    /**
     * @var int $userId
     */
    public $userId;

    /**
     * @var string $privilege
     */
    public $privilege;
}
