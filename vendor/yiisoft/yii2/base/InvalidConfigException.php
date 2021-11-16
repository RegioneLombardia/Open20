<?php
/**
 */

namespace yii\base;

/**
 * InvalidConfigException represents an exception caused by incorrect object configuration.
 *
 * @since 2.0
 */
class InvalidConfigException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Invalid Configuration';
    }
}
