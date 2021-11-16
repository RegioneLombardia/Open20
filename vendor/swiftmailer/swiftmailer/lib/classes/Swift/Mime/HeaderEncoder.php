<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Interface for all Header Encoding schemes.
 *
 */
interface Swift_Mime_HeaderEncoder extends Swift_Encoder
{
    /**
     * Get the MIME name of this content encoding scheme.
     *
     * @return string
     */
    public function getName();
}
