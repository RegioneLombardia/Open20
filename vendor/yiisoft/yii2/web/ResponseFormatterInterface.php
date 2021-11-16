<?php
/**
 */

namespace yii\web;

/**
 * ResponseFormatterInterface specifies the interface needed to format a response before it is sent out.
 *
 * @since 2.0
 */
interface ResponseFormatterInterface
{
    /**
     * Formats the specified response.
     * @param Response $response the response to be formatted.
     */
    public function format($response);
}
