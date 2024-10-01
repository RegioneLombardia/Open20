<?php
/**
 */

namespace ymaker\social\share\base;

/**
 * Base class for drivers for mail services like Gmail and Yahoo.
 *
 * @since 2.0
 */
abstract class AbstractMailDriver extends AbstractDriver
{
    /**
     * @var string
     */
    public $bodyPattern = '{description} - {url}';


    /**
     * {@inheritdoc}
     */
    protected function processShareData()
    {
        $this->title = static::encodeData($this->title);

        $this->appendToData('body', \strtr($this->bodyPattern, [
            '{url}'         => $this->url,
            '{description}' => $this->description,
            '{imageUrl}'    => $this->imageUrl,
        ]));
    }
}
