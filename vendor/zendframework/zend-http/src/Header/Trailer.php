<?php
/**
 */

namespace Zend\Http\Header;

/**
 * @throws Exception\InvalidArgumentException
 */
class Trailer implements HeaderInterface
{
    /**
     * @var string
     */
    protected $value;

    public static function fromString($headerLine)
    {
        list($name, $value) = GenericHeader::splitHeaderLine($headerLine);

        // check to ensure proper header type for this factory
        if (strtolower($name) !== 'trailer') {
            throw new Exception\InvalidArgumentException('Invalid header line for Trailer string: "' . $name . '"');
        }

        // @todo implementation details
        return new static($value);
    }

    public function __construct($value = null)
    {
        if ($value !== null) {
            HeaderValue::assertValid($value);
            $this->value = $value;
        }
    }

    public function getFieldName()
    {
        return 'Trailer';
    }

    public function getFieldValue()
    {
        return (string) $this->value;
    }

    public function toString()
    {
        return 'Trailer: ' . $this->getFieldValue();
    }
}
