<?php
/**
 */
namespace dosamigos\qrcode\formats;

/**
 * Class Youtube formats a string to a valid youtube video link
 *
 * @package dosamigos\qrcode\formats
 */
class Youtube extends FormatAbstract
{
    /**
     * @var string the video ID
     */
    public $videoId;

    /**
     * @return string the formatted string to be encoded
     */
    public function getText()
    {
        return "youtube://{$this->videoId}";
    }

}