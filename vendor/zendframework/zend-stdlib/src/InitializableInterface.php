<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Stdlib;

/**
 * Interface to allow objects to have initialization logic
 */
interface InitializableInterface
{
    /**
     * Init an object
     *
     * @return void
     */
    public function init();
}
