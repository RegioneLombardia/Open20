<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Body Signer Interface used to apply Body-Based Signature to a message.
 *
 */
interface Swift_Signers_BodySigner extends Swift_Signer
{
    /**
     * Change the Swift_Signed_Message to apply the singing.
     *
     * @return self
     */
    public function signMessage(Swift_Message $message);

    /**
     * Return the list of header a signer might tamper.
     *
     * @return array
     */
    public function getAlteredHeaders();
}
