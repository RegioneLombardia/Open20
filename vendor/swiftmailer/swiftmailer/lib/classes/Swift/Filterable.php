<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Allows StreamFilters to operate on a stream.
 *
 */
interface Swift_Filterable
{
    /**
     * Add a new StreamFilter, referenced by $key.
     *
     * @param string $key
     */
    public function addFilter(Swift_StreamFilter $filter, $key);

    /**
     * Remove an existing filter using $key.
     *
     * @param string $key
     */
    public function removeFilter($key);
}
