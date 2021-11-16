<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * A factory for creating CharacterReaders.
 *
 */
interface Swift_CharacterReaderFactory
{
    /**
     * Returns a CharacterReader suitable for the charset applied.
     *
     * @param string $charset
     *
     * @return Swift_CharacterReader
     */
    public function getReaderFor($charset);
}
