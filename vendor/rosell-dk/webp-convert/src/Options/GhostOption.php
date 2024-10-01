<?php

namespace WebPConvert\Options;

use WebPConvert\Options\Option;
use WebPConvert\Options\Exceptions\InvalidOptionValueException;

/**
 * Ghost option
 *
 * @package    WebPConvert
 * @since      Class available since Release 2.0.0
 */
class GhostOption extends Option
{

    protected $typeId = 'ghost';

    public function getValueForPrint()
    {
        return '(not defined for this converter)';
    }
}
