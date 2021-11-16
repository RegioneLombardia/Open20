<?php

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog\Handler;

use Gelf\IMessagePublisher;
use Gelf\PublisherInterface;
use Gelf\Publisher;
use InvalidArgumentException;
use Monolog\Logger;
use Monolog\Formatter\GelfMessageFormatter;

/**
 * Handler to send messages to a Graylog2 (http://www.graylog2.org) server
 *
 */
class GelfHandler extends AbstractProcessingHandler
{
    /**
     * @var Publisher|PublisherInterface|IMessagePublisher the publisher object that sends the message to the server
     */
    protected $publisher;

    /**
     * @param PublisherInterface|IMessagePublisher|Publisher $publisher a publisher object
     * @param int                                            $level     The minimum logging level at which this handler will be triggered
     * @param bool                                           $bubble    Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct($publisher, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        if (!$publisher instanceof Publisher && !$publisher instanceof IMessagePublisher && !$publisher instanceof PublisherInterface) {
            throw new InvalidArgumentException('Invalid publisher, expected a Gelf\Publisher, Gelf\IMessagePublisher or Gelf\PublisherInterface instance');
        }

        $this->publisher = $publisher;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        $this->publisher->publish($record['formatted']);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new GelfMessageFormatter();
    }
}
