<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Infra\Repository;

use Kerianmm\Fleet\Domain\Exception\VehicleNotFound;
use Kerianmm\Fleet\Domain\Model\Vehicle;
use Kerianmm\Fleet\Domain\Repository\VehicleRepositoryInterface;

/**
 * TODO: use sqlite
 *  - Table fleets with id and user_id
 *  - Table vehicles with plate_number and location
 *  - Table fleets_vehicles with plate_number and fleet_id
 *
 * Not done because pending time without any dependency
 */
final class VehicleRepository implements VehicleRepositoryInterface
{
    public function __construct(private array $vehicles = [])
    {
    }

    public function save(Vehicle $vehicle): void
    {
        $this->vehicles[$vehicle->plateNumber] = $vehicle;
    }

    /**
     * @inheritDoc
     */
    public function find(string $plateNumber): Vehicle
    {
        return $this->vehicles[$plateNumber] ?? throw new VehicleNotFound($plateNumber);
    }
}