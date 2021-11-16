<?php
/**
 */

namespace yii\queue\beanstalk;

use yii\helpers\Console;
use yii\queue\cli\Action;

/**
 * Info about queue status.
 *
 */
class InfoAction extends Action
{
    /**
     * @var Queue
     */
    public $queue;


    /**
     * Info about queue status.
     */
    public function run()
    {
        Console::output($this->format('Statistical information about the tube:', Console::FG_GREEN));

        foreach ($this->queue->getStatsTube() as $key => $value) {
            Console::stdout($this->format("- $key: ", Console::FG_YELLOW));
            Console::output($value);
        }
    }
}
