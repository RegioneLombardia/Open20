<?php
/**
 */

namespace yii\httpclient;

/**
 * ParserInterface represents HTTP response message parser.
 *
 * @since 2.0
 */
interface ParserInterface
{
    /**
     * Parses given HTTP response instance.
     * @param Response $response HTTP response instance.
     * @return mixed parsed content data.
     */
    public function parse(Response $response);
}