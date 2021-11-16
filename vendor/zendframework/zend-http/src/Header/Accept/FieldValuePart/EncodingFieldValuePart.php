<?php
/**
 */

namespace Zend\Http\Header\Accept\FieldValuePart;

/**
 * Field Value Part
 *
 */
class EncodingFieldValuePart extends AbstractFieldValuePart
{
    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->getInternalValues()->type;
    }
}
