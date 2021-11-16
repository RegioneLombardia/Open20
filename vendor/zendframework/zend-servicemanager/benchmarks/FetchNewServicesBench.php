<?php
/**
 */

namespace ZendBench\ServiceManager;

use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;
use Zend\ServiceManager\ServiceManager;

/**
 * @Revs(1000)
 * @Iterations(10)
 * @Warmup(2)
 */
class FetchNewServicesBench
{
    /**
     * @var ServiceManager
     */
    private $sm;

    public function __construct()
    {
        $this->sm = new ServiceManager([
            'factories' => [
                'factory1' => BenchAsset\FactoryFoo::class,
            ],
            'invokables' => [
                'invokable1' => BenchAsset\Foo::class,
            ],
            'services' => [
                'service1' => new \stdClass(),
                'config' => [],
            ],
            'aliases' => [
                'factoryAlias1'          => 'factory1',
                'recursiveFactoryAlias1' => 'factoryAlias1',
                'recursiveFactoryAlias2' => 'recursiveFactoryAlias1',
            ],
            'abstract_factories' => [
                BenchAsset\AbstractFactoryFoo::class,
            ],
        ]);
    }

    public function benchFetchFactory1()
    {
        $sm = clone $this->sm;

        $sm->get('factory1');
    }

    public function benchBuildFactory1()
    {
        $sm = clone $this->sm;

        $sm->build('factory1');
    }

    public function benchFetchInvokable1()
    {
        $sm = clone $this->sm;

        $sm->get('invokable1');
    }

    public function benchBuildInvokable1()
    {
        $sm = clone $this->sm;

        $sm->build('invokable1');
    }

    public function benchFetchService1()
    {
        $sm = clone $this->sm;

        $sm->get('service1');
    }

    public function benchFetchFactoryAlias1()
    {
        $sm = clone $this->sm;

        $sm->build('factoryAlias1');
    }

    public function benchBuildFactoryAlias1()
    {
        $sm = clone $this->sm;

        $sm->build('factoryAlias1');
    }

    public function benchFetchRecursiveFactoryAlias1()
    {
        $sm = clone $this->sm;

        $sm->build('recursiveFactoryAlias1');
    }

    public function benchBuildRecursiveFactoryAlias1()
    {
        $sm = clone $this->sm;

        $sm->build('recursiveFactoryAlias1');
    }

    public function benchFetchRecursiveFactoryAlias2()
    {
        $sm = clone $this->sm;

        $sm->build('recursiveFactoryAlias2');
    }

    public function benchBuildRecursiveFactoryAlias2()
    {
        $sm = clone $this->sm;

        $sm->build('recursiveFactoryAlias2');
    }

    public function benchFetchAbstractFactoryFoo()
    {
        $sm = clone $this->sm;

        $sm->get('foo');
    }

    public function benchBuildAbstractFactoryFoo()
    {
        $sm = clone $this->sm;

        $sm->build('foo');
    }
}
