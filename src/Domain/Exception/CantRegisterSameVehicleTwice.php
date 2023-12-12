<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Domain\Exception;

use Kerianmm\Fleet\Domain\Model\Fleet;
use Kerianmm\Fleet\Domain\Model\Vehicle;

final class CantRegisterSameVehicleTwice extends \DomainException
{
    public function __construct(Vehicle $vehicle, Fleet $fleet)
    {
        parent::__construct(sprintf(
            'Can\'t register same vehicle "%s" twice in the fleet "%s"',
            $vehicle->plateNumber,
            $fleet->id,
        ));
    }
}