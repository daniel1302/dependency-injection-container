<?php
namespace Vallheru\Conteiner;


use PHPUnit\Framework\TestCase;
use Vallheru\Container\Container;
use Vallheru\Container\Mock\ServiceA;
use Vallheru\Container\Mock\ServiceB;

class ContainerTest extends TestCase
{

    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/Mock/ServiceA.php';
        require_once __DIR__.'/Mock/ServiceB.php';

        Container::addDefinition('service_a', function(Container $container) {
            return new ServiceA();
        });

        Container::addDefinition('service_b', function(Container $container) {
            $serviceA = $container->get('service_a');

            return new ServiceB($serviceA);
        });

        Container::addDefinition('non_shared_a', function(Container $container) {
            return new ServiceA();
        }, ['shared' => false]);
    }

    public function testAddDefinition()
    {
        $container = new Container();


        $this->assertEquals(true, $container->has('service_a'));
        $this->assertEquals(true, $container->has('service_b'));
        $this->assertEquals(false, $container->has('service_xxx'));
        $this->assertInstanceOf(ServiceA::class, $container->get('service_a'));
        $this->assertInstanceOf(ServiceB::class, $container->get('service_b'));
    }

    public function testSharedService()
    {
        $container = new Container();

        $a1 = $container->get('service_a');
        $a2 = $container->get('service_a');
        $a3 = $container->get('service_a');

        $this->assertEquals($a1->getRandom(), $a2->getRandom());
        $this->assertEquals($a2->getRandom(), $a3->getRandom());
    }

    public function testNonSharedService()
    {
        $container = new Container();

        $b1 = $container->get('non_shared_a');
        $b2 = $container->get('non_shared_a');
        $b3 = $container->get('non_shared_a');


        $this->assertNotEquals($b1->getRandom(), $b2->getRandom());
        $this->assertNotEquals($b1->getRandom(), $b3->getRandom());
        $this->assertNotEquals($b3->getRandom(), $b2->getRandom());
    }

    /**
     * @expectedException \Vallheru\Container\Exception\ContainerException
     * @expectedExceptionCode 11
     * @expectedExceptionMessage Service service_a exist in container and cannot be overwrite
     */
    public function testThrownExceptionForDuplicateService()
    {
        Container::addDefinition('service_a', function() {
            return new ServiceA();
        });
    }
}
