<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Infra\Shared;

use Kerianmm\Fleet\App\Shared\Container\ContainerInterface;
use Kerianmm\Fleet\App\Shared\Query\QueryBusInterface;
use Kerianmm\Fleet\App\Shared\Query\QueryHandlerInterface;
use Kerianmm\Fleet\App\Shared\Query\QueryInterface;

final class QueryBus implements QueryBusInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function dispatch(QueryInterface $command): mixed
    {
        $handler = $this->container->get($command::class);
        if (!$handler instanceof QueryHandlerInterface) {
            throw new \LogicException();
        }

        return $handler($command);
    }
}