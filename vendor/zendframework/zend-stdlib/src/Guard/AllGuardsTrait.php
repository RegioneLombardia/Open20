<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Stdlib\Guard;

/**
 * An aggregate for all guard traits
 */
trait AllGuardsTrait
{
    use ArrayOrTraversableGuardTrait;
    use EmptyGuardTrait;
    use NullGuardTrait;
}
