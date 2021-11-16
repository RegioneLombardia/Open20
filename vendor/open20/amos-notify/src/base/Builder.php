<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\base
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\base;

use open20\amos\core\user\User;

/**
 * Interface Builder
 * @package open20\amos\notificationmanager\base
 */
interface Builder
{
    /**
     * @param array $resultset
     * @param User $user
     * @return string
     */
    public function renderEmail(array $resultset, User $user);

    /**
     * @param array $resultset
     * @return string
     */
    public function getSubject(array $resultset);

    /**
     * @param array $resultSetNormal
     * @param array $resultSetNetwork
     * @param User $user
     * @return mixed
     */
}
