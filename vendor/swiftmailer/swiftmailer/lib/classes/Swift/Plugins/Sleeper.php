<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Sleeps for a duration of time.
 *
 */
interface Swift_Plugins_Sleeper
{
    /**
     * Sleep for $seconds.
     *
     * @param int $seconds
     */
    public function sleep($seconds);
}
