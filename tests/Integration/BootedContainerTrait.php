<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Tests\Integration;

use Kerianmm\Fleet\App\Shared\Command\CommandBusInterface;
use Kerianmm\Fleet\App\Shared\Container\ContainerInterface;
use Kerianmm\Fleet\Domain\Repository\FleetRepositoryInterface;
use Kerianmm\Fleet\Domain\Repository\VehicleRepositoryInterface;
use Kerianmm\Fleet\Infra\Shared\Container\Container;

trait BootedContainerTrait
{
    private ContainerInterface $container;
    private FleetRepositoryInterface $fleetRepository;
    private VehicleRepositoryInterface $vehicleRepository;
    private CommandBusInterface $commandBus;

    public function __construct()
    {
        $this->container = Container::boot();

        // @phpstan-ignore-next-line
        $this->fleetRepository   = $this->container->get(FleetRepositoryInterface::class);
        // @phpstan-ignore-next-line
        $this->vehicleRepository = $this->container->get(VehicleRepositoryInterface::class);

        // @phpstan-ignore-next-line
        $this->commandBus = $this->container->get(CommandBusInterface::class);
    }
}