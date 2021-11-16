<?php
/**
 */

namespace yii\httpclient;

use yii\base\BaseObject;

/**
 * UrlEncodedParser parses HTTP message content as 'application/x-www-form-urlencoded'.
 *
 * @since 2.0
 */
class UrlEncodedParser extends BaseObject implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(Response $response)
    {
        $data = [];
        parse_str($response->getContent(), $data);
        return $data;
    }
}