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
class ExtractPriorityQueueBench
{
    public function __construct()
    {
        $this->splPriorityQueue  = new SplPriorityQueue();
        $this->fastPriorityQueue = new FastPriorityQueue();
        $this->priorityQueue     = new PriorityQueue();

        for ($i = 0; $i < 5000; $i += 1) {
            $priority = rand(1, 100);
            $this->splPriorityQueue->insert('foo', $priority);
            $this->fastPriorityQueue->insert('foo', $priority);
            $this->priorityQueue->insert('foo', $priority);
        }
    }

    public function benchExtractSplPriorityQueue()
    {
        $this->splPriorityQueue->extract();
    }

    public function benchExtractPriorityQueue()
    {
        $this->priorityQueue->extract();
    }

    public function benchExtractFastPriorityQueue()
    {
        $this->fastPriorityQueue->extract();
    }
}
