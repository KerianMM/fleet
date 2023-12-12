<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Infra\Shared\Container;

use Kerianmm\Fleet\App\Command\CreateFleetCommand;
use Kerianmm\Fleet\App\Command\CreateFleetCommandHandler;
use Kerianmm\Fleet\App\Command\ParkCommand;
use Kerianmm\Fleet\App\Command\ParkCommandHandler;
use Kerianmm\Fleet\App\Command\RegisterVehicleCommand;
use Kerianmm\Fleet\App\Command\RegisterVehicleCommandHandler;
use Kerianmm\Fleet\App\Query\LocationQuery;
use Kerianmm\Fleet\App\Query\LocationQueryHandler;
use Kerianmm\Fleet\App\Shared\Command\CommandBusInterface;
use Kerianmm\Fleet\App\Shared\Container\ContainerInterface;
use Kerianmm\Fleet\App\Shared\Query\QueryBusInterface;
use Kerianmm\Fleet\Domain\Repository\FleetRepositoryInterface;
use Kerianmm\Fleet\Domain\Repository\VehicleRepositoryInterface;
use Kerianmm\Fleet\Infra\Repository\FleetRepository;
use Kerianmm\Fleet\Infra\Repository\VehicleRepository;
use Kerianmm\Fleet\Infra\Shared\CommandBus;
use Kerianmm\Fleet\Infra\Shared\QueryBus;

final class Container implements ContainerInterface
{
    public function __construct(
        private array $services,
    ) {
    }

    public static function boot(): static
    {
        $container = new self([
            FleetRepositoryInterface::class   => new FleetRepository(),
            VehicleRepositoryInterface::class => new VehicleRepository(),
        ]);

        $container->set(CommandBusInterface::class, new CommandBus($container));
        $container->set(QueryBusInterface::class, new QueryBus($container));
        $container->set(CreateFleetCommand::class, new CreateFleetCommandHandler($container));
        $container->set(RegisterVehicleCommand::class, new RegisterVehicleCommandHandler($container));
        $container->set(ParkCommand::class, new ParkCommandHandler($container));
        $container->set(LocationQuery::class, new LocationQueryHandler($container));

        return $container;
    }

    public function set(string $id, object $service): static
    {
        $this->services[$id] = $service;

        return $this;
    }

    public function get(string $id): ?object
    {
        return $this->services[$id] ?? null;
    }
}