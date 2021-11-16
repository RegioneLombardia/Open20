<?php

/*
 * (c) 2018 Christian Schmidt
 *
 */

/**
 * AddressEncoderException when the specified email address is in a format that
 * cannot be encoded by a given address encoder.
 *
 */
class Swift_AddressEncoderException extends Swift_RfcComplianceException
{
    protected $address;

    public function __construct(string $message, string $address)
    {
        parent::__construct($message);

        $this->address = $address;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
