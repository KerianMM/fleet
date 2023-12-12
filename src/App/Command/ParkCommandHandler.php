<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\App\Command;

use Kerianmm\Fleet\App\Shared\Command\CommandHandlerInterface;
use Kerianmm\Fleet\App\Shared\Command\CommandInterface;
use Kerianmm\Fleet\App\Shared\Container\ContainerInterface;
use Kerianmm\Fleet\Domain\Model\Location;
use Kerianmm\Fleet\Domain\Model\Vehicle;
use Kerianmm\Fleet\Domain\Repository\VehicleRepositoryInterface;

final readonly class ParkCommandHandler implements CommandHandlerInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @param ParkCommand $command
     */
    public function __invoke(CommandInterface $command): Vehicle
    {
        /** @var VehicleRepositoryInterface $vehicleRepository */
        $vehicleRepository = $this->container->get(VehicleRepositoryInterface::class);

        $vehicle = $vehicleRepository->find($command->plateNumber);
//        Is fleetId really usefull ?
//        $vehicle = $vehicleRepository->find($command->fleetId, $command->plateNumber);
        $vehicle->localize(new Location($command->latitude, $command->longitude));

        $vehicleRepository->save($vehicle);

        return $vehicle;
    }
}