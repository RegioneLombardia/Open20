<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Allows customization of Messages on-the-fly.
 *
 */
interface Swift_Plugins_Decorator_Replacements
{
    /**
     * Return the array of replacements for $address.
     *
     * This method is invoked once for every single recipient of a message.
     *
     * If no replacements can be found, an empty value (NULL) should be returned
     * and no replacements will then be made on the message.
     *
     * @param string $address
     *
     * @return array
     */
    public function getReplacementsFor($address);
}
