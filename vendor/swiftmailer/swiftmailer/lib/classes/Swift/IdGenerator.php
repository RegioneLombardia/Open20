<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Message ID generator.
 */
interface Swift_IdGenerator
{
    /**
     * Returns a globally unique string to use for Message-ID or Content-ID.
     *
     * @return string
     */
    public function generateId();
}
