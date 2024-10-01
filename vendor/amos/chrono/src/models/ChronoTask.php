<?php

namespace amos\chrono\models;

use amos\chrono\base\ChronoTaskStatus;
use amos\chrono\models\base\ChronoTask as ChronoImportTask;
use Cron\CronExpression;
use RuntimeException;
use Exception;
use Yii;
use const PHP_EOL;

/**
 * This is the model class for table "chrono_task".
 */
class ChronoTask extends ChronoImportTask
{
    /**
     * The user the command should run as.
     *
     * @var string
     */
    protected $user;

    /**
     * The location that output should be sent to.
     *
     * @var string
     */
    protected $output = null;

    /**
     * The string for redirection.
     *
     * @var array
     */
    protected $redirect = ' > ';

    /**
     * Decide if errors will be displayed.
     *
     * @var bool
     */
    protected $omitErrors = true;

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->output = $this->getDefaultOutput();
    }

    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    /**
     *
     * @param type $currentTime
     * @return type
     */
    public function getNextRunDate($currentTime = 'now')
    {
        $ret = '';

        try {
            $ret = CronExpression::factory($this->schedule)
                ->getNextRunDate($currentTime)
                ->format('Y-m-d H:i:s');
        } catch (Exception $ex) {           
            Yii::$app->getSession()->addFlash('danger',
                Yii::t('amoschrono', '#impossible_date'));
        } catch (RuntimeException $rex) {            
            Yii::$app->getSession()->addFlash('danger',
                Yii::t('amoschrono', '#impossible_date'));
        }
        return $ret;
    }

    /**
     *
     * @param type $str
     */
    public function writeLine($str)
    {
        echo $str.PHP_EOL;
    }

    /**
     * Mark the task as started
     */
    public function start()
    {
        $this->started_at = date('Y-m-d H:i:s');
        $this->save(false);
    }

    /**
     * Mark the task as stopped.
     */
    public function stop()
    {
        $this->last_run   = $this->started_at;
        $this->next_run   = $this->getNextRunDate();
        $this->started_at = null;
        $this->save(false);
    }

    /**
     * @param bool $forceRun
     * @return bool
     */
    public function shouldRun($forceRun = false)
    {
        $isDue     = strtotime($this->next_run) <= time() || $this->status == ChronoTaskStatus::ERROR;
        $isRunning = $this->status == ChronoTaskStatus::WORKING;
        $overdue   = false;
        if ((strtotime($this->started_at) + $this->overdueThreshold) <= strtotime("now")) {
            $overdue = true;
        }

        return ($this->active && ((!$isRunning && ($isDue || $forceRun)) || ($isRunning
            && $overdue)));
    }

    /**
     * 
     */
    public function run()
    {
        exec($this->buildCommand());
    }

    /**
     *
     * @return type
     */
    public function buildCommand()
    {
        $command = $this->command.$this->redirect.$this->output.(($this->omitErrors)
                ? ' 2>&1 &' : '');
        return $this->user ? 'sudo -u '.$this->user.' '.$command : $command;
    }

    /**
     *
     * @return string
     */
    public function getDefaultOutput()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return 'NUL';
        } else {
            return '/dev/null';
        }
    }

    /**
     * 
     * @return string
     */
    public function getStatusLabel()
    {
        return ChronoTaskStatus::getStateLabel($this->status);
    }
}