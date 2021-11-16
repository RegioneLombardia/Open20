<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Logs events in the Transport system.
 *
 */
interface Swift_Plugins_Logger
{
    /**
     * Add a log entry.
     *
     * @param string $entry
     */
    public function add($entry);

    /**
     * Clear the log contents.
     */
    public function clear();

    /**
     * Get this log as a string.
     *
     * @return string
     */
    public function dump();
}
