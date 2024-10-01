<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\planner
 * @category   Planner
 */

namespace open20\design\utility;
 
use Yii;

class DateUtility
{

    /**
     * ISO-8601 - Return the first day of the first week of the year
     * @param integer|null $year Year in the format '2019' - If null it set the current year
     * @return string Date in the format '2018-12-31'
     */
    public static function getFirstWeekDayByYear($year = null)
    {
        try {
            if (empty($year)) {
                $year = date('Y');
            }
            $firstDay = date("Y-m-d", strtotime("{$year}W01"));
            return $firstDay;
        } catch (Exception $ex) {
            Yii::trace($ex->getTrace(), 'DateUtility - getFirstWeekDayByYear()');
            return null;
        }
    }

    /**
     * Return the first day of the week from the week number of year
     * @param integer $number ISO-8601 week number of year, weeks starting on Monday - Example: 42 (the 42nd week in the year)
     * @param integer|null $year Year in the format '2019' - If null it set the current year
     * @return string Date in the format '2018-12-31'
     */
    public static function getFirstWeekDayByWeekNumber($number, $year = null)
    {
        try {
            if (empty($year)) {
                $year = date('Y');
            }
            $firstDayYear = self::getFirstWeekDayByYear($year);
            $number--;
            $dayAdd       = (($number >= 0) ? $number : 0) * 7;
            $firstDay     = date('Y-m-d', strtotime($firstDayYear." + $dayAdd days"));
            return $firstDay;
        } catch (Exception $ex) {
            Yii::trace($ex->getTrace(), 'DateUtility - getFirstDayWeekByWeekNumber()');
            return null;
        }
    }

    /**
     * ISO-8601 week number of year, weeks starting on Monday - Example: 42 (the 42nd week in the year)
     * @param string|null $date Date in the format '2018-12-31' - If null it set the current date
     * @return string|null Null only in case of errors
     */
    public static function getWeekNumberByDate($date = null)
    {
        try {
            if (empty($date)) {
                $date = date('Y-m-d');
            }
            $number = date('W', strtotime($date));
            return $number;
        } catch (Exception $ex) {
            Yii::trace($ex->getTrace(), 'DateUtility - getWeekNumberByDate()');
            return null;
        }
    }

    /**
     * Return the first working day of the week (monday)
     * @param string|null $date Date in the format '2018-12-31' - If null it set the current date
     * @return string Null only in case of errors
     */
    public static function getFirstWeekDayByDate($date = null)
    {
        try {
            if (empty($date)) {
                $date = date('Y-m-d');
            }
            $number   = self::getWeekNumberByDate($date);
            $firstDay = self::getFirstWeekDayByWeekNumber($number);
            return $firstDay;
        } catch (Exception $ex) {
            Yii::trace($ex->getTrace(), 'DateUtility - getFirstWeekDayByDate()');
            return null;
        }
    }

    /**
     * Return the last working day of the week (friday)
     * @param string|null $date Date in the format '2018-12-31' - If null it set the current date
     * @return string Null only in case of errors
     */
    public static function getLastWeekDayByDate($date = null)
    {
        try {
            if (empty($date)) {
                $date = date('Y-m-d');
            }
            $firstDay = self::getFirstWeekDayByDate($date);
            $lastDay  = date('Y-m-d', strtotime($firstDay." + 4 days"));
            return $lastDay;
        } catch (Exception $ex) {
            Yii::trace($ex->getTrace(), 'DateUtility - getLastWeekDayByDate()');
            return null;
        }
    }

    /**
     * ISO-8601 numeric representation of the day of the week - 1 (for Monday) through 7 (for Sunday)
     * @param string $date Date in the format '2018-12-31' - If null it set the current date
     * @return integer Null only in case of errors
     */
    public static function getDayNumberOfTheWeek($date = null)
    {
        try {
            if (empty($date)) {
                $date = date('Y-m-d');
            }
            $number = date('N', strtotime($date));
            return $number;
        } catch (Exception $ex) {
            Yii::trace($ex->getTrace(), 'DateUtility - getDayNumberOfTheWeek()');
            return null;
        }
    }

    /**
     * A full textual representation of a next month, such as January or March
     * @param string $date
     * @param type $format
     * @return string Null only in case of errors
     */
    public static function getNextMonth($date = null, $format = 'php:F')
    {
        try {
            if (empty($date)) {
                $date = date('Y-m-d');
            }
            $arrDate = explode('-', $date);
            if (isset($arrDate[1]) && $arrDate[1] == '01') {
                $date = $arrDate[0].'-'.str_pad($arrDate[1] + 1, 2, '0', STR_PAD_LEFT).'-'.$arrDate[2];
                return \Yii::$app->formatter->asDate("$date", $format);
            }
            return \Yii::$app->formatter->asDate("$date + 1 month", $format);
        } catch (Exception $ex) {
            Yii::trace($ex->getTrace(), 'DateUtility - getNextMonth()');
            return null;
        }
    }

    /**
     * A full textual representation of a previous month, such as January or March
     * @param string $date
     * @param type $format
     * @return string Null only in case of errors
     */
    public static function getPreviousMonth($date = null, $format = 'php:F')
    {
        try {
            if (empty($date)) {
                $date = date('Y-m-d');
            }
            $arrDate = explode('-', $date);
            if (isset($arrDate[1]) && $arrDate[1] == '03') {
                $date = $arrDate[0].'-'.str_pad($arrDate[1] - 1, 2, '0', STR_PAD_LEFT).'-'.$arrDate[2];
                return \Yii::$app->formatter->asDate("$date", $format);
            }
            return \Yii::$app->formatter->asDate("$date - 1 month", $format);
        } catch (Exception $ex) {
            Yii::trace($ex->getTrace(), 'DateUtility - getPreviousMonth()');
            return null;
        }
    }

    /**
     * A full textual representation of a next month, such as January or March
     * @param string $date
     * @param type $format
     * @return string Null only in case of errors
     */
    public static function getMonth($date = null, $format = 'php:F')
    {
        try {
            if (empty($date)) {
                $date = date('Y-m-d');
            }
            return \Yii::$app->formatter->asDate("$date", $format);
        } catch (Exception $ex) {
            Yii::trace($ex->getTrace(), 'DateUtility - getNextMonth()');
            return null;
        }
    }

    /**
     * A full date
     * @param string $date
     * @param type $format
     * @return string Null only in case of errors
     */
    public static function getDate($date = null, $format = 'php:d/m/Y')
    {
        try {
            if (empty($date)) {
                $date = date('Y-m-d');
            }
            return \Yii::$app->formatter->asDate("$date", $format);
        } catch (Exception $ex) {
            pr($ex->getTrace());die;
            Yii::trace($ex->getTrace(), 'DateUtility - getDate()');
            return null;
        }
    }

    /**
     * A full date
     * @param string $date
     * @param type $format
     * @return string Null only in case of errors
     */
    public static function getDateHour($date = null, $format = 'php:d/m/Y H:i:s')
    {
        try {
            if (empty($date)) {
                $date = date('Y-m-d H:i:s');
            }
            return \Yii::$app->formatter->asDate("$date", $format);
        } catch (Exception $ex) {
            pr($ex->getTrace());die;
            Yii::trace($ex->getTrace(), 'DateUtility - getDateHour()');
            return null;
        }
    }

    /**
     * A full hour
     * @param string $date
     * @param type $format
     * @return string Null only in case of errors
     */
    public static function getHour($date = null, $format = 'php:H:i:s')
    {
        try {
            if (empty($date)) {
                $date = date('H:i:s');
            }
            return \Yii::$app->formatter->asDate("$date", $format);
        } catch (Exception $ex) {
            pr($ex->getTrace());die;
            Yii::trace($ex->getTrace(), 'DateUtility - getDateHour()');
            return null;
        }
    }

    /**
     * 
     * @param string $date
     * @return string
     */
    public static function getNumberMonth($date = null)
    {
        try {
            if (empty($date)) {
                $date = date('Y-m-d');
            }
            $month = date("n", strtotime("$date"));
            return $month;
        } catch (Exception $ex) {
            Yii::trace($ex->getTrace(), 'DateUtility - getNumberMonth()');
            return null;
        }
    }
}