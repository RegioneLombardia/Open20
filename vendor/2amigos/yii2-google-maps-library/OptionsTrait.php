<?php
/**
 */
namespace dosamigos\google\maps;


/**
 * OptionsTrait
 *
 * Contains common functions for option classes.
 *
 * @package dosamigos\google\maps
 */
trait OptionsTrait
{
    /**
     * @return string the js constructor of the object
     */
    public function getJs()
    {
        $name = $this->getName(false) ? "var {$this->getName()} = " : "";
        $end = $this->getName(false) ? ";" : "";
        $options = $this->getEncodedOptions();
        return "{$name}{$options}{$end}";
    }
} 