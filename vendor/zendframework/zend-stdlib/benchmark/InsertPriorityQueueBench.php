<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace ZendBench\Stdlib;

use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;
use Zend\Stdlib\FastPriorityQueue;
use Zend\Stdlib\PriorityQueue;
use Zend\Stdlib\SplPriorityQueue;

/**
 * @Revs(1000)
 * @Iterations(10)
 * @Warmup(2)
 */
class InsertPriorityQueueBench
{
    public function __construct()
    {
        $this->splPriorityQueue  = new SplPriorityQueue();
        $this->fastPriorityQueue = new FastPriorityQueue();
        $this->priorityQueue     = new PriorityQueue();
    }

    public function benchInsertSplPriorityQueue()
    {
        $this->splPriorityQueue->insert('foo', rand(1, 100));
    }

    public function benchInsertPriorityQueue()
    {
        $this->priorityQueue->insert('foo', rand(1, 100));
    }

    public function benchInsertFastPriorityQueue()
    {
        $this->fastPriorityQueue->insert('foo', rand(1, 100));
    }
}
