<?php
/**
 */

namespace ymaker\social\share\drivers;

use ymaker\social\share\base\AbstractDriver;

/**
 * Driver for Trello.
 *
 *
 * @since 2.2
 */
class Trello extends AbstractDriver
{
    /**
     * {@inheritdoc}
     */
    protected function processShareData()
    {
        $this->url = static::encodeData($this->url);
        $this->title = static::encodeData($this->title);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildLink()
    {
        return 'https://trello.com/add-card'
            . '?url={url}'
            . '&name={title}'
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMetaTags()
    {
        return [['property' => 'og:description', 'content' => '{description}']];
    }
}
