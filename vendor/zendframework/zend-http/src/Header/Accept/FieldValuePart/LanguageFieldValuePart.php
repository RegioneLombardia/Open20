<?php
/**
 */

namespace Zend\Http\Header\Accept\FieldValuePart;

/**
 * Field Value Part
 *
 */
class LanguageFieldValuePart extends AbstractFieldValuePart
{
    public function getLanguage()
    {
        return $this->getInternalValues()->typeString;
    }

    public function getPrimaryTag()
    {
        return $this->getInternalValues()->type;
    }

    public function getSubTag()
    {
        return $this->getInternalValues()->subtype;
    }
}
