<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Stdlib\Guard;

/**
 * Provide a guard method against empty data
 */
trait EmptyGuardTrait
{
    /**
     * Verify that the data is not empty
     *
     * @param  mixed  $data           the data to verify
     * @param  string $dataName       the data name
     * @param  string $exceptionClass FQCN for the exception
     * @throws \Exception
     */
    protected function guardAgainstEmpty(
        $data,
        $dataName = 'Argument',
        $exceptionClass = 'Zend\Stdlib\Exception\InvalidArgumentException'
    ) {
        if (empty($data)) {
            $message = sprintf('%s cannot be empty', $dataName);
            throw new $exceptionClass($message);
        }
    }
}
