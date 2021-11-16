<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Stdlib;

interface DispatchableInterface
{
    /**
     * Dispatch a request
     *
     * @param RequestInterface $request
     * @param null|ResponseInterface $response
     * @return Response|mixed
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response = null);
}
