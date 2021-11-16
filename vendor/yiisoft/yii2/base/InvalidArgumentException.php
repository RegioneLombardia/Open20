<?php
/**
 */

namespace yii\base;

/**
 * InvalidArgumentException represents an exception caused by invalid arguments passed to a method.
 *
 * @since 2.0.14
 */
class InvalidArgumentException extends InvalidParamException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Invalid Argument';
    }
}
