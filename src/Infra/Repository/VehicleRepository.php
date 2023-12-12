<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Infra\Repository;

use Kerianmm\Fleet\Domain\Exception\VehicleNotFound;
use Kerianmm\Fleet\Domain\Model\Vehicle;
use Kerianmm\Fleet\Domain\Repository\VehicleRepositoryInterface;

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