<?php
/**
 */

namespace Zend\Http\Header\Accept\FieldValuePart;

/**
 * Field Value Part
 *
 */
class AcceptFieldValuePart extends AbstractFieldValuePart
{
    /**
     * @return string
     */
    public function getSubtype()
    {
        return $this->getInternalValues()->subtype;
    }

    /**
     * @return string
     */
    public function getSubtypeRaw()
    {
        return $this->getInternalValues()->subtypeRaw;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->getInternalValues()->format;
    }
}
