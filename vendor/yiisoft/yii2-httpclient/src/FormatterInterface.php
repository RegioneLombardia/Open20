<?php
/**
 */

namespace yii\httpclient;

/**
 * FormatterInterface represents HTTP request message formatter.
 *
 * @since 2.0
 */
interface FormatterInterface
{
    /**
     * Formats given HTTP request message.
     * @param Request $request HTTP request instance.
     * @return Request formatted request.
     */
    public function format(Request $request);
}