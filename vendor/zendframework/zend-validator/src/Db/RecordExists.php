<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Validator\Db;

use Zend\Validator\Exception;

/**
 * Confirms a record exists in a table.
 */
class RecordExists extends AbstractDb
{
    public function isValid($value)
    {
        /*
         * Check for an adapter being defined. If not, throw an exception.
         */
        if (null === $this->adapter) {
            throw new Exception\RuntimeException('No database adapter present');
        }

        $valid = true;
        $this->setValue($value);

        $result = $this->query($value);
        if (! $result) {
            $valid = false;
            $this->error(self::ERROR_NO_RECORD_FOUND);
        }

        return $valid;
    }
}
