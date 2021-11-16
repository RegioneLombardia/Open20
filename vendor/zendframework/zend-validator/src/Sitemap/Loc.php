<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Validator\Sitemap;

use Zend\Uri;
use Zend\Validator\AbstractValidator;

/**
 * Validates whether a given value is valid as a sitemap <loc> value
 *
 *
 */
class Loc extends AbstractValidator
{
    /**
     * Validation key for not valid
     *
     */
    const NOT_VALID = 'sitemapLocNotValid';
    const INVALID   = 'sitemapLocInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_VALID => "The input is not a valid sitemap location",
        self::INVALID   => "Invalid type given. String expected",
    ];

    /**
     * Validates if a string is valid as a sitemap location
     *
     *
     * @param  string  $value  value to validate
     * @return bool
     */
    public function isValid($value)
    {
        if (! is_string($value)) {
            $this->error(self::INVALID);
            return false;
        }

        $this->setValue($value);
        $uri = Uri\UriFactory::factory($value);
        if (! $uri->isValid()) {
            $this->error(self::NOT_VALID);
            return false;
        }

        return true;
    }
}
