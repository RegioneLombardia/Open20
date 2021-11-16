<?php
/**
 */

namespace yii\base;

/**
 * ViewNotFoundException represents an exception caused by view file not found.
 *
 * @since 2.0.10
 */
class ViewNotFoundException extends InvalidArgumentException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'View not Found';
    }
}
