<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Observes changes for a Mime entity's ContentEncoder.
 *
 */
interface Swift_Mime_EncodingObserver
{
    /**
     * Notify this observer that the observed entity's ContentEncoder has changed.
     */
    public function encoderChanged(Swift_Mime_ContentEncoder $encoder);
}
