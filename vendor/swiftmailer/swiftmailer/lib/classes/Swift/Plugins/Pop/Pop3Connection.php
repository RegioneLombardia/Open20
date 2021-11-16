<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Pop3Connection interface for connecting and disconnecting to a POP3 host.
 *
 */
interface Swift_Plugins_Pop_Pop3Connection
{
    /**
     * Connect to the POP3 host and throw an Exception if it fails.
     *
     * @throws Swift_Plugins_Pop_Pop3Exception
     */
    public function connect();

    /**
     * Disconnect from the POP3 host and throw an Exception if it fails.
     *
     * @throws Swift_Plugins_Pop_Pop3Exception
     */
    public function disconnect();
}
