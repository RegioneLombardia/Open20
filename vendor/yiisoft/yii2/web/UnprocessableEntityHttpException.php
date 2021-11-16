<?php
/**
 */

namespace yii\web;

/**
 * UnprocessableEntityHttpException represents an "Unprocessable Entity" HTTP
 * exception with status code 422.
 *
 * Use this exception to inform that the server understands the content type of
 * the request entity and the syntax of that request entity is correct but the server
 * was unable to process the contained instructions. For example, to return form
 * validation errors.
 *
 * @since 2.0.7
 */
class UnprocessableEntityHttpException extends HttpException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(422, $message, $code, $previous);
    }
}
