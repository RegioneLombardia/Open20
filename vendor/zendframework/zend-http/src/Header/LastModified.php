<?php
/**
 */

namespace Zend\Http\Header;

/**
 * Last-Modified Header
 *
 */
class LastModified extends AbstractDate
{
    /**
     * Get header name
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'Last-Modified';
    }
}
