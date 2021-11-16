<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Feed\Reader\Http;

interface ClientInterface
{
    /**
     * Make a GET request to a given URI
     *
     * @param  string $uri
     * @return ResponseInterface
     */
    public function get($uri);
}
