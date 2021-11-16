<?php declare(strict_types=1);

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog\Handler;

use Monolog\ResettableInterface;

/**
 * Helper trait for implementing ProcessableInterface
 *
 * This trait is present in monolog 1.x to ease forward compatibility.
 *
 */
trait ProcessableHandlerTrait
{
    /**
     * @var callable[]
     */
    protected $processors = [];

    /**
     * {@inheritdoc}
     * @suppress PhanTypeMismatchReturn
     */
    public function pushProcessor($callback): HandlerInterface
    {
        array_unshift($this->processors, $callback);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function popProcessor(): callable
    {
        if (!$this->processors) {
            throw new \LogicException('You tried to pop from an empty processor stack.');
        }

        return array_shift($this->processors);
    }

    /**
     * Processes a record.
     */
    protected function processRecord(array $record): array
    {
        foreach ($this->processors as $processor) {
            $record = $processor($record);
        }

        return $record;
    }

    protected function resetProcessors(): void
    {
        foreach ($this->processors as $processor) {
            if ($processor instanceof ResettableInterface) {
                $processor->reset();
            }
        }
    }
}
