<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Helper;

/**
 * HelperInterface is the interface all helpers must implement.
 *
 */
interface HelperInterface
{
    /**
     * Sets the helper set associated with this helper.
     */
    public function setHelperSet(HelperSet $helperSet = null);

    /**
     * Gets the helper set associated with this helper.
     *
     * @return HelperSet A HelperSet instance
     */
    public function getHelperSet();

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName();
}
