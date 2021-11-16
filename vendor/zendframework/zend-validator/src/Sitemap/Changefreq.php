<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Validator\Sitemap;

use Zend\Validator\AbstractValidator;

/**
 * Validates whether a given value is valid as a sitemap <changefreq> value
 *
 */
class Changefreq extends AbstractValidator
{
    /**
     * Validation key for not valid
     *
     */
    const NOT_VALID = 'sitemapChangefreqNotValid';
    const INVALID   = 'sitemapChangefreqInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_VALID => "The input is not a valid sitemap changefreq",
        self::INVALID   => "Invalid type given. String expected",
    ];

    /**
     * Valid change frequencies
     *
     * @var array
     */
    protected $changeFreqs = [
        'always',  'hourly', 'daily', 'weekly',
        'monthly', 'yearly', 'never'
    ];

    /**
     * Validates if a string is valid as a sitemap changefreq
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
        if (! is_string($value)) {
            return false;
        }

        if (! in_array($value, $this->changeFreqs, true)) {
            $this->error(self::NOT_VALID);
            return false;
        }

        return true;
    }
}
