<?php
/**
 */

namespace Zend\Http\Header;

/**
 * If-Unmodified-Since Header
 *
 */
class IfUnmodifiedSince extends AbstractDate
{
    /**
     * Get header name
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'If-Unmodified-Since';
    }
}
