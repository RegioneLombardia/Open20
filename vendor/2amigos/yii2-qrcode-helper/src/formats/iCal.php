<?php
/**
 */
namespace dosamigos\qrcode\formats;

/**
 * iCal creates a valid iCal format string
 *
 * @package dosamigos\qrcode\formats
 */
class iCal extends FormatAbstract
{
    /**
     * @var string the event summary
     */
    public $summary;
    /**
     * @var integer the unix timestamp of the start date of the event
     */
    public $dtStart;
    /**
     * @var integer the unix timestamp of the end date of the event
     */
    public $dtEnd;

    /**
     * @inheritdoc
     */
    public function getText()
    {
        $data = [];
        $data[] = "BEGIN:VEVENT";
        $data[] = "SUMMARY:{$this->summary}";
        $data[] = "DTSTART:{$this->unixToiCal($this->dtStart)}";
        $data[] = "DTEND:{$this->unixToiCal($this->dtEnd)}";
        $data[] = "END:VEVENT";

        return implode("\n", $data);
    }

    /**
     * Converts a unix timestamp to iCal format. Timezones are assumed to be included into the timestamp.
     *
     * @param int $value the unix timestamp to convert
     *
     * @return bool|string the formatted date
     */
    protected function unixToiCal($value) {
        return date("Ymd\THis\Z", $value);
    }

}