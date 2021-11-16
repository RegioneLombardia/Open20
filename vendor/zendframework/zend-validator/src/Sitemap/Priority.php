<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Validator\Sitemap;

use Zend\Validator\AbstractValidator;

/**
 * Validates whether a given value is valid as a sitemap <priority> value
 *
 */
class Priority extends AbstractValidator
{
    /**
     * Validation key for not valid
     *
     */
    const NOT_VALID = 'sitemapPriorityNotValid';
    const INVALID   = 'sitemapPriorityInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_VALID => "The input is not a valid sitemap priority",
        self::INVALID   => "Invalid type given. Numeric string, integer or float expected",
    ];

    /**
     * Validates if a string is valid as a sitemap priority
     *
     *
     * @param  string  $value  value to validate
     * @return bool
     */
    public function isValid($value)
    {
        if (! is_numeric($value)) {
            $this->error(self::INVALID);
            return false;
        }

        $this->setValue($value);
        $value = (float) $value;
        if ($value < 0 || $value > 1) {
            $this->error(self::NOT_VALID);
            return false;
        }

        return true;
    }
}
