<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\record
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\record;

/**
 * Interface NotifyRecordInterface
 * @package open20\amos\notificationmanager\record
 */
interface NotifyRecordInterface
{
    /**
     * @return bool
     */
    public function isNews();

    /**
     * @return array
     */
    public function createOrderClause();

    /**
     * @return bool
     */
    public function sendNotification();

    /**
     * @return bool
     */
    public function sendCommunication();
}
