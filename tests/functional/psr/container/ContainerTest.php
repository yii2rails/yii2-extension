<?php

namespace tests\functional\psr\container;

use yii2rails\extension\common\exceptions\NotFoundException;
use yii2rails\extension\psr\container\Container;
use yii2rails\extension\encrypt\libs\JwtService;
use yii2tool\test\Test\Unit;

class ContainerTest extends Unit {
	
	const PACKAGE = 'yii2rails/yii2-extension';

    public function testDefine() {
        $definitions = [
            'service1' => Service1::class,
            'service2' => [
                'class' => Service2::class,
                'param1' => 111,
                'param2' => 222,
                'param3' => 333,
            ],
            'service3' => new Service3,
            'service4' => null,
        ];
        $container = new Container($definitions);
        $this->tester->assertEquals(Service1::class, get_class($container->service1));
        $this->tester->assertEquals(Service2::class, get_class($container->service2));
        $this->tester->assertEquals(111, $container->service2->param1);
        $this->tester->assertEquals(222, $container->service2->param2);
        $this->tester->assertEquals(333, $container->service2->param3);
        $this->tester->assertEquals(Service3::class, get_class($container->service3));
        try {
            $container->service4;
            $this->tester->assertBad();
        } catch (NotFoundException $e) {
            $this->tester->assertExceptionMessage('Component "service4" not found!', $e);
        }
    }

    public function testDefine2() {
        $definitions = [
            'domain1' => new Container([
                'service1' => Service1::class,
                'service2' => Service2::class,
            ]),
            'domain2' => new Container([
                'service3' => Service3::class,
            ]),
        ];

        $container = new Container($definitions);

        $this->tester->assertEquals(Service1::class, get_class($container->domain1->service1));
        $this->tester->assertEquals(Service2::class, get_class($container->domain1->service2));
        $this->tester->assertEquals(Service3::class, get_class($container->domain2->service3));
    }

    public function testDefine3() {
        $definitions = [
            'domain1' => [
                'class' => Container::class,
                'components' => [
                    'service1' => Service1::class,
                    'service2' => Service2::class,
                ],

            ],
            'domain2' => [
                'class' => Container::class,
                'components' => [
                    'service3' => Service3::class,
                ],
            ],
        ];

        $container = new Container($definitions);

        $this->tester->assertEquals(Service1::class, get_class($container->domain1->service1));
        $this->tester->assertEquals(Service2::class, get_class($container->domain1->service2));
        $this->tester->assertEquals(Service3::class, get_class($container->domain2->service3));
    }

    public function testDefine4() {
        $definitions = [
            Service1::class => Service2::class,
            'service1' => Service1::class,
            'service2' => Service2::class,
            'service3' => Service3::class,
        ];

        $container = new Container($definitions);
        $this->tester->assertEquals(Service2::class, get_class($container->get(Service1::class)));
        //$this->tester->assertEquals(Service2::class, get_class($container->get('service1')));
    }

}

class Domain1 {

}

class Domain2 {

}

class Service1 {

}

class Service2 {

    public $param1;
    public $param2;
    public $param3;

}

class Service3 {

}