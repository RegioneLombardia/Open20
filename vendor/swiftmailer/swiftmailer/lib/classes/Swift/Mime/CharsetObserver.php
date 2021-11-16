<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Observes changes in an Mime entity's character set.
 *
 */
interface Swift_Mime_CharsetObserver
{
    /**
     * Notify this observer that the entity's charset has changed.
     *
     * @param string $charset
     */
    public function charsetChanged($charset);
}
