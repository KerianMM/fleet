<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Domain\Exception;

use Kerianmm\Fleet\Domain\Model\Location;
use Kerianmm\Fleet\Domain\Model\Vehicle;

final class CantLocalizeVehicleToTheSameLocation extends \DomainException
{
    public function __construct(Vehicle $vehicle, Location $location)
    {
        parent::__construct(sprintf(
            'Can\'t localize my vehicle "%s" to the same location (%s, %s) two times in a row',
            $vehicle->plateNumber,
            $location->latitude,
            $location->longitude,
        ));
    }
}