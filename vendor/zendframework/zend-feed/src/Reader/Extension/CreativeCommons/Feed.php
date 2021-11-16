<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Feed\Reader\Extension\CreativeCommons;

use Zend\Feed\Reader\Extension;

class Feed extends Extension\AbstractFeed
{
    /**
     * Get the entry proscription
     *
     * @param int $index
     * @return string|null
     */
    public function getProscription($index = 0)
    {
        $proscriptions = $this->getProscriptions();

        if (isset($proscriptions[$index])) {
            return $proscriptions[$index];
        }

        return;
    }

    /**
     * Get the entry proscriptions
     *
     * @return array
     */
    public function getProscriptions()
    {
        $name = 'proscriptions';
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $proscriptions = [];
        $list = $this->xpath->evaluate('channel/cc:proscription');

        if ($list->length) {
            foreach ($list as $proscription) {
                $proscriptions[] = $proscription->nodeValue;
            }

            $proscriptions = array_unique($proscriptions);
        }

        $this->data[$name] = $proscriptions;

        return $this->data[$name];
    }

    /**
     * Register Creative Commons namespaces
     *
     * @return void
     */
    protected function registerNamespaces()
    {
        $this->xpath->registerNamespace('cc', 'http://backend.userland.com/creativeCommonsRssModule');
    }
}
