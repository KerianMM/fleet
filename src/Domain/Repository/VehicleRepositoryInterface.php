<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Domain\Repository;

use Kerianmm\Fleet\Domain\Exception\VehicleNotFound;
use Kerianmm\Fleet\Domain\Model\Vehicle;

interface VehicleRepositoryInterface
{
    public function save(Vehicle $vehicle): void;

    /**
     * @throws VehicleNotFound
     */
    public function find(string $plateNumber): Vehicle;
}