<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\App\Command;

use Kerianmm\Fleet\App\Shared\Command\CommandHandlerInterface;
use Kerianmm\Fleet\App\Shared\Command\CommandInterface;
use Kerianmm\Fleet\App\Shared\Container\ContainerInterface;
use Kerianmm\Fleet\Domain\Exception\VehicleNotFound;
use Kerianmm\Fleet\Domain\Model\Fleet;
use Kerianmm\Fleet\Domain\Model\Vehicle;
use Kerianmm\Fleet\Domain\Repository\FleetRepositoryInterface;
use Kerianmm\Fleet\Domain\Repository\VehicleRepositoryInterface;

final readonly class RegisterVehicleCommandHandler implements CommandHandlerInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @param RegisterVehicleCommand $command
     */
    public function __invoke(CommandInterface $command): Fleet
    {
        /** @var VehicleRepositoryInterface $vehicleRepository */
        $vehicleRepository = $this->container->get(VehicleRepositoryInterface::class);

        try {
            $vehicle = $vehicleRepository->find($command->plateNumber);
        } catch (VehicleNotFound) {
            $vehicleRepository->save($vehicle = new Vehicle($command->plateNumber));
        }

        /** @var FleetRepositoryInterface $fleetRepository */
        $fleetRepository = $this->container->get(FleetRepositoryInterface::class);
        $fleet           = $fleetRepository->find($command->fleetId);
        $fleet->registerVehicle($vehicle);

        $fleetRepository->save($fleet);

        return $fleet;
    }
}