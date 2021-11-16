<?php
/**
 */

namespace yii\httpclient;

/**
 * Exception represents an exception that is caused during HTTP requests.
 *
 * @since 2.0
 */
class Exception extends \yii\base\Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'HTTP Client Exception';
    }
}
