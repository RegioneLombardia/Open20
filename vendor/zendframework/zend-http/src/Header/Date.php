<?php
/**
 */

namespace Zend\Http\Header;

/**
 * Date Header
 *
 */
class Date extends AbstractDate
{
    /**
     * Get header name
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'Date';
    }
}
