<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Infra\Shared;

use Kerianmm\Fleet\App\Shared\Command\CommandBusInterface;
use Kerianmm\Fleet\App\Shared\Command\CommandHandlerInterface;
use Kerianmm\Fleet\App\Shared\Command\CommandInterface;
use Kerianmm\Fleet\App\Shared\Container\ContainerInterface;

final class CommandBus implements CommandBusInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function dispatch(CommandInterface $command): mixed
    {
        $handler = $this->container->get($command::class);
        if (!$handler instanceof CommandHandlerInterface) {
            throw new \LogicException();
        }

        return $handler($command);
    }
}