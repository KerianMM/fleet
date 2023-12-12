<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Domain\Model;

use Kerianmm\Fleet\Domain\Exception\CantRegisterSameVehicleTwice;

final class Fleet
{
    private function __construct(
        public readonly string $id,
        public readonly string $userId,
        /** @var array<string, Vehicle> $vehicles */
        private array $vehicles,
    ) {
    }

    public static function create(string $userId): static
    {
        return new self(
            id: uniqid(),
            userId: $userId,
            vehicles: [],
        );
    }

    /**
     * @throws CantRegisterSameVehicleTwice
     */
    public function registerVehicle(Vehicle $vehicle): void
    {
        if ($this->hasVehicle($vehicle)) {
            throw new CantRegisterSameVehicleTwice($vehicle, $this);
        }

        $this->vehicles[$vehicle->plateNumber] = $vehicle;
    }

    public function hasVehicle(Vehicle $vehicle): bool
    {
        return isset($this->vehicles[$vehicle->plateNumber]);
    }

    public function vehicles(): array
    {
        return $this->vehicles;
    }
}