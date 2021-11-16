<?php declare(strict_types=1);

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog\Handler;

use Monolog\Formatter\FormatterInterface;

/**
 * Interface to describe loggers that have a formatter
 *
 * This interface is present in monolog 1.x to ease forward compatibility.
 *
 */
interface FormattableHandlerInterface
{
    /**
     * Sets the formatter.
     *
     * @param  FormatterInterface $formatter
     * @return HandlerInterface   self
     */
    public function setFormatter(FormatterInterface $formatter): HandlerInterface;

    /**
     * Gets the formatter.
     *
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface;
}
