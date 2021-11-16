<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Provides timestamp data.
 *
 */
interface Swift_Plugins_Timer
{
    /**
     * Get the current UNIX timestamp.
     *
     * @return int
     */
    public function getTimestamp();
}
