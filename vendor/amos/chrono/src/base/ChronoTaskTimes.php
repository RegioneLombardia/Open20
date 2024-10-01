<?php

namespace amos\chrono\base;

use amos\chrono\Module;

class ChronoTaskTimes
{
    const cron_eprexion = '* * * * * *';

    public function hourly()
    {
        return '0 * * * * *';
    }

    public function daily()
    {
        return '0 0 * * * *';
    }

    public function twiceDaily()
    {
        return '0 1,13 * * * *';
    }

    /**
     * Splice the given value into the given position of the expression.
     *
     * @param  int $position
     * @param  string $value
     * @return string
     */
    protected function spliceIntoPosition($position, $value)
    {
        $segments                = explode(' ', static::cron_eprexion);
        $segments[$position - 1] = $value;
        return implode(' ', $segments);
    }

    /**
     *
     * @return string
     */
    public function weekdays()
    {
        return $this->spliceIntoPosition(5, '1-5');
    }

    /**
     * Schedule the event to run only on Mondays.
     *
     * @return $this
     */
    public function mondays()
    {
        return $this->days(1);
    }

    /**
     * Set the days of the week the command should run on.
     *
     * @param  array|int $days
     * @return $this
     */
    public function days($days)
    {
        $days = is_array($days) ? $days : func_get_args();
        return $this->spliceIntoPosition(5, implode(',', $days));
    }

    /**
     * Schedule the event to run only on Tuesdays.
     *
     * @return $this
     */
    public function tuesdays()
    {
        return $this->days(2);
    }

    /**
     * Schedule the event to run only on Wednesdays.
     *
     * @return $this
     */
    public function wednesdays()
    {
        return $this->days(3);
    }

    /**
     * Schedule the event to run only on Thursdays.
     *
     * @return $this
     */
    public function thursdays()
    {
        return $this->days(4);
    }

    /**
     * Schedule the event to run only on Fridays.
     *
     * @return $this
     */
    public function fridays()
    {
        return $this->days(5);
    }

    /**
     * Schedule the event to run only on Saturdays.
     *
     * @return $this
     */
    public function saturdays()
    {
        return $this->days(6);
    }

    /**
     * Schedule the event to run only on Sundays.
     *
     * @return $this
     */
    public function sundays()
    {
        return $this->days(0);
    }

    /**
     * Schedule the event to run weekly.
     *
     * @return $this
     */
    public function weekly()
    {
        return '0 0 * * 0 *';
    }

    public function createCronArrayList(){
        $values = [];

        $values[''] = Module::t('amoschrono','#select');
        $values[$this->daily()] = 'daily';
        $values[$this->weekly()] = 'weekly';
        $values[$this->mondays()] = 'mondays';
        $values[$this->twiceDaily()] = 'twiceDaily';
        $values[$this->mondays()] = 'mondays';
        $values[$this->tuesdays()] = 'tuesdays';
        $values[$this->wednesdays()] = 'wednesdays';
         $values[$this->thursdays()] = 'thursdays';
        $values[$this->fridays()] = 'fridays';
        $values[$this->saturdays()] = 'saturdays';
        $values[$this->sundays()] = 'sundays';
        return $values;
    }
}