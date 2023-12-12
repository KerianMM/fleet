<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Tests\Unit\Infra\Shared\Container;

use Kerianmm\Fleet\Infra\Shared\Container\Container;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    public function testContainer(): void
    {
        $container = new Container(['first' => $firstService = new \stdClass()]);
        $container->set('second', $secondService = new \stdClass());

        static::assertSame($firstService, $container->get('first'));
        static::assertSame($secondService, $container->get('second'));
        static::assertNull($container->get('undefined'));
    }
}
