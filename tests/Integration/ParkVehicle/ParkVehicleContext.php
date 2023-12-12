<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Tests\Integration\ParkVehicle;

use Behat\Behat\Context\Context;
use Kerianmm\Fleet\App\Command\ParkCommand;
use Kerianmm\Fleet\Domain\Exception\CantLocalizeVehicleToTheSameLocation;
use Kerianmm\Fleet\Domain\Model\Fleet;
use Kerianmm\Fleet\Domain\Model\Location;
use Kerianmm\Fleet\Domain\Model\Vehicle;
use Kerianmm\Fleet\Tests\Integration\BootedContainerTrait;

final class ParkVehicleContext implements Context
{
    use BootedContainerTrait;

    private ?Fleet $fleet                                                               = null;
    private ?Vehicle $vehicle                                                           = null;
    private ?Location $location                                                         = null;
    // Implementation doubt : a simple bool is enough
    private ?CantLocalizeVehicleToTheSameLocation $cantLocalizeVehicleToTheSameLocation = null;

    /**
     * @Given /^my fleet$/
     */
    public function withFleet(): void
    {
        $this->fleet = Fleet::create('any-user');
        $this->fleetRepository->save($this->getFleet());
    }

    /**
     * @Given /^a vehicle$/
     */
    public function withVehicle(): void
    {
        $this->vehicle = new Vehicle('AZ-123-ER');
        $this->vehicleRepository->save($this->getVehicle());
    }

    /**
     * @Given /^a location$/
     */
    public function withLocation(): void
    {
        $this->location = new Location(123., 456.);
    }

    /**
     * @Given /^I have registered this vehicle into my fleet$/
     */
    public function withRegisteredVehicle(): void
    {
        $this->getFleet()->registerVehicle($this->getVehicle());
        $this->fleetRepository->save($this->getFleet());
    }

    /**
     * @Given /^my vehicle has been parked into this location$/
     */
    public function withParkedVehicle(): void
    {
        $this->getVehicle()->localize($this->getLocation());
        $this->vehicleRepository->save($this->getVehicle());
    }

    /**
     * @When /^I park my vehicle at this location$/
     */
    public function parkVehicle(): void
    {
        $this->commandBus->dispatch(new ParkCommand(
            fleetId: $this->getFleet()->id,
            plateNumber: $this->getVehicle()->plateNumber,
            latitude: $this->getLocation()->latitude,
            longitude: $this->getLocation()->longitude,
        ));
    }

    /**
     * @When /^I try to park my vehicle at this location$/
     */
    public function parkVehicleTwice(): void
    {
        try {
            $this->commandBus->dispatch(new ParkCommand(
                fleetId: $this->getFleet()->id,
                plateNumber: $this->getVehicle()->plateNumber,
                latitude: $this->getLocation()->latitude,
                longitude: $this->getLocation()->longitude,
            ));
        } catch (CantLocalizeVehicleToTheSameLocation $cantLocalizeVehicleToTheSameLocation) {
            $this->cantLocalizeVehicleToTheSameLocation = $cantLocalizeVehicleToTheSameLocation;
        }
    }

    /**
     * @Then /^the known location of my vehicle should verify this location$/
     */
    public function assertVehicleLocation(): void
    {
        $location = $this->getVehicle()->location();
        if (
            $this->getLocation()->latitude !== $location?->latitude ||
            $this->getLocation()->longitude !== $location->longitude
        ) {
            throw new \RuntimeException('The known location of my vehicle should verify this location');
        }
    }

    /**
     * @Then /^I should be informed that my vehicle is already parked at this location$/
     */
    public function assertCantLocalizeVehicleToTheSameLocation(): void
    {
        if (null === $this->cantLocalizeVehicleToTheSameLocation) {
            throw new \RuntimeException('I should be informed that my vehicle is already parked at this location');
        }
    }

    private function getFleet(): Fleet
    {
        return $this->fleet ?? throw new \RuntimeException(
            sprintf('Please, init "%s::fleet" field before using', __CLASS__),
        );
    }

    private function getVehicle(): Vehicle
    {
        return $this->vehicle ?? throw new \RuntimeException(
            sprintf('Please, init "%s::vehicle" field before using', __CLASS__),
        );
    }

    private function getLocation(): Location
    {
        return $this->location ?? throw new \RuntimeException(
            sprintf('Please, init "%s::location" field before using', __CLASS__),
        );
    }
}