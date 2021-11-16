<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Stdlib\ArrayUtils;

/**
 * Marker interface: can be used to replace keys completely in {@see ArrayUtils::merge()} operations
 */
interface MergeReplaceKeyInterface
{
    /**
     * @return mixed
     */
    public function getData();
}
