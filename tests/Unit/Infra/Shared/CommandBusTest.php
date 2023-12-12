<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Tests\Unit\Infra\Shared;

use Kerianmm\Fleet\App\Shared\Command\CommandHandlerInterface;
use Kerianmm\Fleet\App\Shared\Command\CommandInterface;
use Kerianmm\Fleet\App\Shared\Container\ContainerInterface;
use Kerianmm\Fleet\Infra\Shared\CommandBus;
use PHPUnit\Framework\TestCase;

final class CommandBusTest extends TestCase
{
    public function testHandlerNotFound(): void
    {
        $command = new class implements CommandInterface {};

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(static::once())
            ->method('get')
            ->with($command::class)
            ->willReturn(null);

        self::expectException(\LogicException::class);

        $commandBus = new CommandBus($container);
        $commandBus->dispatch($command);
    }

    public function testHandlerFound(): void
    {
        $command        = new class implements CommandInterface {};
        $commandHandler = new class implements CommandHandlerInterface {
            public function __invoke(CommandInterface $command): mixed
            {
                return 'done';
            }
        };

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(static::once())
            ->method('get')
            ->with($command::class)
            ->willReturn($commandHandler);

        $commandBus = new CommandBus($container);
        static::assertSame('done', $commandBus->dispatch($command));
    }
}