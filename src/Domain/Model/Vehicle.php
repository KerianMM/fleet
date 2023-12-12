<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Domain\Model;

use Kerianmm\Fleet\Domain\Exception\CantLocalizeVehicleToTheSameLocation;

final class Vehicle
{
    public function __construct(
        public readonly string $plateNumber,
        private ?Location $location = null,
    ) {
    }

    public function localize(Location $location): void
    {
        $currentLocation = $this->location();
        if (
            $currentLocation?->latitude === $location->latitude &&
            $currentLocation->longitude === $location->longitude
        ) {
            throw new CantLocalizeVehicleToTheSameLocation($this, $location);
        }

        $this->location = $location;
    }

    public function location(): ?Location
    {
        return $this->location;
    }
}