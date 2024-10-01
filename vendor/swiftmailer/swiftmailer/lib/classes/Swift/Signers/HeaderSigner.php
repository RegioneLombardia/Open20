<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Header Signer Interface used to apply Header-Based Signature to a message.
 *
 */
interface Swift_Signers_HeaderSigner extends Swift_Signer, Swift_InputByteStream
{
    /**
     * Exclude an header from the signed headers.
     *
     * @param string $header_name
     *
     * @return self
     */
    public function ignoreHeader($header_name);

    /**
     * Prepare the Signer to get a new Body.
     *
     * @return self
     */
    public function startBody();

    /**
     * Give the signal that the body has finished streaming.
     *
     * @return self
     */
    public function endBody();

    /**
     * Give the headers already given.
     *
     * @return self
     */
    public function setHeaders(Swift_Mime_SimpleHeaderSet $headers);

    /**
     * Add the header(s) to the headerSet.
     *
     * @return self
     */
    public function addSignature(Swift_Mime_SimpleHeaderSet $headers);

    /**
     * Return the list of header a signer might tamper.
     *
     * @return array
     */
    public function getAlteredHeaders();
}
