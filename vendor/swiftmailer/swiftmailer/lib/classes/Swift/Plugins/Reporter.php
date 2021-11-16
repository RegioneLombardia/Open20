<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * The Reporter plugin sends pass/fail notification to a Reporter.
 *
 */
interface Swift_Plugins_Reporter
{
    /** The recipient was accepted for delivery */
    const RESULT_PASS = 0x01;

    /** The recipient could not be accepted */
    const RESULT_FAIL = 0x10;

    /**
     * Notifies this ReportNotifier that $address failed or succeeded.
     *
     * @param string $address
     * @param int    $result  from {@link RESULT_PASS, RESULT_FAIL}
     */
    public function notify(Swift_Mime_SimpleMessage $message, $address, $result);
}
