<?php
/**
 */

namespace yii\web;

/**
 * NotAcceptableHttpException represents a "Not Acceptable" HTTP exception with status code 406.
 *
 * Use this exception when the client requests a Content-Type that your
 * application cannot return. Note that, according to the HTTP 1.1 specification,
 * you are not required to respond with this status code in this situation.
 *
 * @since 2.0
 */
class NotAcceptableHttpException extends HttpException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(406, $message, $code, $previous);
    }
}
