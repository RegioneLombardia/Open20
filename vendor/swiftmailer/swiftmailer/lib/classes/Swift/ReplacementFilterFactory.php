<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Creates StreamFilters.
 *
 */
interface Swift_ReplacementFilterFactory
{
    /**
     * Create a filter to replace $search with $replace.
     *
     * @param mixed $search
     * @param mixed $replace
     *
     * @return Swift_StreamFilter
     */
    public function createFilter($search, $replace);
}
