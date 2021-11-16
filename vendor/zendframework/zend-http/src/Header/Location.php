<?php
/**
 */

namespace Zend\Http\Header;

/**
 * Location Header
 *
 */
class Location extends AbstractLocation
{
    /**
     * Return header name
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'Location';
    }
}
