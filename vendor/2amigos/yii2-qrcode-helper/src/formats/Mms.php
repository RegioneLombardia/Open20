<?php
/**
 *
 * MMS.php
 *
 * Date: 15/03/15
 * Time: 18:36
 */

namespace dosamigos\qrcode\formats;


/**
 * Mms formats a string to properly create a Sms QrCode
 *
 * @package dosamigos\qrcode\formats
 */
class Mms extends Sms
{
    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getText()
    {
        $data = [];
        $data[] = "MMSTO";
        $data[] = $this->phone;
        $data[] = $this->msg;

        return implode(":", array_filter($data));
    }
}