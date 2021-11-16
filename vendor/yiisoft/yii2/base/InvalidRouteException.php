<?php
/**
 */

namespace yii\base;

/**
 * InvalidRouteException represents an exception caused by an invalid route.
 *
 * @since 2.0
 */
class InvalidRouteException extends UserException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Invalid Route';
    }
}
