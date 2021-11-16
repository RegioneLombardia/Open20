<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Provides an abstract way of specifying recipients for batch sending.
 *
 */
interface Swift_Mailer_RecipientIterator
{
    /**
     * Returns true only if there are more recipients to send to.
     *
     * @return bool
     */
    public function hasNext();

    /**
     * Returns an array where the keys are the addresses of recipients and the
     * values are the names. e.g. ('foo@bar' => 'Foo') or ('foo@bar' => NULL).
     *
     * @return array
     */
    public function nextRecipient();
}
