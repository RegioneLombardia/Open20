<?php
/**
 */

namespace Zend\Http\Header\Accept\FieldValuePart;

/**
 * Field Value Part
 *
 */
class CharsetFieldValuePart extends AbstractFieldValuePart
{
    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->getInternalValues()->type;
    }
}
