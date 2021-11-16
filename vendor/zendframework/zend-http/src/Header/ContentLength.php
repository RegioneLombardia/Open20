<?php
/**
 */

namespace Zend\Http\Header;

/**
 * @throws Exception\InvalidArgumentException
 */
class ContentLength implements HeaderInterface
{
    /**
     * @var string
     */
    protected $value;

    public static function fromString($headerLine)
    {
        list($name, $value) = GenericHeader::splitHeaderLine($headerLine);

        // check to ensure proper header type for this factory
        if (strtolower($name) !== 'content-length') {
            throw new Exception\InvalidArgumentException(sprintf(
                'Invalid header line for Content-Length string: "%s"',
                $name
            ));
        }

        // @todo implementation details
        return new static($value);
    }

    public function __construct($value = null)
    {
        if (null !== $value) {
            HeaderValue::assertValid($value);
            $this->value = $value;
        }
    }

    public function getFieldName()
    {
        return 'Content-Length';
    }

    public function getFieldValue()
    {
        return (string) $this->value;
    }

    public function toString()
    {
        return 'Content-Length: ' . $this->getFieldValue();
    }
}
