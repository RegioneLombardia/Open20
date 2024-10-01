<?php

/*
 * (c) 2018 Christian Schmidt
 *
 */

/**
 * Email address encoder.
 *
 */
interface Swift_AddressEncoder
{
    /**
     * Encodes an email address.
     *
     * @throws Swift_AddressEncoderException if the email cannot be represented in
     *                                       the encoding implemented by this class
     */
    public function encodeString(string $address): string;
}
