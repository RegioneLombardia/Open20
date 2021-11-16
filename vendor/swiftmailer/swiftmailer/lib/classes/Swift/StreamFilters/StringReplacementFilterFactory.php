<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Creates filters for replacing needles in a string buffer.
 *
 */
class Swift_StreamFilters_StringReplacementFilterFactory implements Swift_ReplacementFilterFactory
{
    /** Lazy-loaded filters */
    private $filters = [];

    /**
     * Create a new StreamFilter to replace $search with $replace in a string.
     *
     * @param string $search
     * @param string $replace
     *
     * @return Swift_StreamFilter
     */
    public function createFilter($search, $replace)
    {
        if (!isset($this->filters[$search][$replace])) {
            if (!isset($this->filters[$search])) {
                $this->filters[$search] = [];
            }

            if (!isset($this->filters[$search][$replace])) {
                $this->filters[$search][$replace] = [];
            }

            $this->filters[$search][$replace] = new Swift_StreamFilters_StringReplacementFilter($search, $replace);
        }

        return $this->filters[$search][$replace];
    }
}
