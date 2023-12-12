<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\App\Query;

use Kerianmm\Fleet\App\Shared\Container\ContainerInterface;
use Kerianmm\Fleet\App\Shared\Query\QueryHandlerInterface;
use Kerianmm\Fleet\App\Shared\Query\QueryInterface;
use Kerianmm\Fleet\Domain\Exception\FleetNotFound;
use Kerianmm\Fleet\Domain\Model\Location;
use Kerianmm\Fleet\Domain\Model\Vehicle;
use Kerianmm\Fleet\Domain\Repository\FleetRepositoryInterface;

final class LocationQueryHandler implements QueryHandlerInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @param LocationQuery $query
     */
    public function __invoke(QueryInterface $query): ?Location
    {
        /** @var FleetRepositoryInterface $repository */
        $repository = $this->container->get(FleetRepositoryInterface::class);

        try {
        /** @var Vehicle|null $vehicle */
        $vehicle = $repository->find($query->fleetId)->vehicles()[$query->plateNumber] ?? null;
        return $vehicle?->location();
        } catch (FleetNotFound) {
            return null;
        }
    }
}