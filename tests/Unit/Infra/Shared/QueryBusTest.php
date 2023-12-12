<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Tests\Unit\Infra\Shared;

use Kerianmm\Fleet\App\Shared\Container\ContainerInterface;
use Kerianmm\Fleet\App\Shared\Query\QueryHandlerInterface;
use Kerianmm\Fleet\App\Shared\Query\QueryInterface;
use Kerianmm\Fleet\Infra\Shared\QueryBus;
use PHPUnit\Framework\TestCase;

final class QueryBusTest extends TestCase
{
    public function testHandlerNotFound(): void
    {
        $command = new class implements QueryInterface {};

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(static::once())
            ->method('get')
            ->with($command::class)
            ->willReturn(null);

        self::expectException(\LogicException::class);

        $commandBus = new QueryBus($container);
        $commandBus->dispatch($command);
    }

    public function testHandlerFound(): void
    {
        $command        = new class implements QueryInterface {};
        $commandHandler = new class implements QueryHandlerInterface {
            public function __invoke(QueryInterface $command): mixed
            {
                return 'done';
            }
        };

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(static::once())
            ->method('get')
            ->with($command::class)
            ->willReturn($commandHandler);

        $commandBus = new QueryBus($container);
        static::assertSame('done', $commandBus->dispatch($command));
    }
}