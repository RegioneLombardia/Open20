<?php
/**
 */

namespace Zend\Http\Header;

/**
 * If-Modified-Since Header
 *
 */
class IfModifiedSince extends AbstractDate
{
    /**
     * Get header name
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'If-Modified-Since';
    }
}
