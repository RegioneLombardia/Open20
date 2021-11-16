<?php
/**
 */

namespace yii\base;

/**
 * InvalidParamException represents an exception caused by invalid parameters passed to a method.
 *
 * @since 2.0
 * @deprecated since 2.0.14. Use [[InvalidArgumentException]] instead.
 */
class InvalidParamException extends \BadMethodCallException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Invalid Parameter';
    }
}
