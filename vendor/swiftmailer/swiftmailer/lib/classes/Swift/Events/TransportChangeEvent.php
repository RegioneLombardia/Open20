<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Generated when the state of a Transport is changed (i.e. stopped/started).
 *
 */
class Swift_Events_TransportChangeEvent extends Swift_Events_EventObject
{
    /**
     * Get the Transport.
     *
     * @return Swift_Transport
     */
    public function getTransport()
    {
        return $this->getSource();
    }
}
