<?php
/*
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 */

namespace PhpCsFixer\Diff\v3_0;

final class ConfigurationException extends InvalidArgumentException
{
    /**
     * @param string          $option
     * @param string          $expected
     * @param mixed           $value
     * @param int             $code
     * @param null|\Exception $previous
     */
    public function __construct(
        $option,
        $expected,
        $value,
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct(
            \sprintf(
                'Option "%s" must be %s, got "%s".',
                $option,
                $expected,
                \is_object($value) ? \get_class($value) : (null === $value ? '<null>' : \gettype($value) . '#' . $value)
            ),
            $code,
            $previous
        );
    }
}
