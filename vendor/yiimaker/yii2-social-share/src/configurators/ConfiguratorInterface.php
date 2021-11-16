<?php
/**
 */

namespace ymaker\social\share\configurators;

/**
 * Interface of configurators of social network drivers.
 *
 * @since 1.0
 */
interface ConfiguratorInterface
{
    /**
     * Returns array with config for social network drivers.
     *
     * @return array
     */
    public function getSocialNetworks();

    /**
     * Returns array with HTML options for share links.
     *
     * @return array
     */
    public function getOptions();

    /**
     * Checks whether driver can register own meta tags in the view.
     *
     * @return bool
     *
     * @since 2.3
     */
    public function canRegisterMetaTags();
}
