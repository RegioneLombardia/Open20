<?php
/**
 */

namespace Zend\Http\Header;

/**
 * Content-Location Header
 *
 */
class ContentLocation extends AbstractLocation
{
    /**
     * Return header name
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'Content-Location';
    }
}
