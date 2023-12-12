<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\App\Command;

use Kerianmm\Fleet\App\Shared\Command\CommandHandlerInterface;
use Kerianmm\Fleet\App\Shared\Command\CommandInterface;
use Kerianmm\Fleet\App\Shared\Container\ContainerInterface;
use Kerianmm\Fleet\Domain\Model\Fleet;
use Kerianmm\Fleet\Domain\Repository\FleetRepositoryInterface;

final readonly class CreateFleetCommandHandler implements CommandHandlerInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @param CreateFleetCommand $command
     */
    public function __invoke(CommandInterface $command): Fleet
    {
        /** @var FleetRepositoryInterface $fleetRepository */
        $fleetRepository = $this->container->get(FleetRepositoryInterface::class);

        $fleet = Fleet::create($command->userId);
        $fleetRepository->save($fleet);

        return $fleet;
    }
}