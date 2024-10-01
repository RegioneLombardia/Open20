<?php
/**
 */

namespace yii\web;

/**
 * MethodNotAllowedHttpException represents a "Method Not Allowed" HTTP exception with status code 405.
 *
 * @since 2.0
 */
class MethodNotAllowedHttpException extends HttpException
{
    /**
     * Constructor.
     * @param string|null $message error message
     * @param int $code error code
     * @param \Throwable|null $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, $previous = null)
    {
        parent::__construct(405, $message, $code, $previous);
    }
}
