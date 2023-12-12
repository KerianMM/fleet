<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Tests\Integration\RegisterVehicle;

use Behat\Behat\Context\Context;
use Kerianmm\Fleet\Domain\Exception\CantRegisterSameVehicleTwice;
use Kerianmm\Fleet\Domain\Model\Fleet;
use Kerianmm\Fleet\Domain\Model\Vehicle;

final class RegisterVehicleContext implements Context
{
    private ?Fleet $fleet                                               = null;
    private ?Fleet $anotherFleet                                        = null;
    private ?Vehicle $vehicle                                           = null;
    // Implementation doubt : a simple bool is enough
    private ?CantRegisterSameVehicleTwice $cantRegisterSameVehicleTwice = null;

    /**
     * @Given /^my fleet$/
     */
    public function withFleet(): void
    {
        $this->fleet = Fleet::create('any-user');
    }

    /**
     * @Given /^the fleet of another user$/
     */
    public function withAnotherFleet(): void
    {
        $this->anotherFleet = Fleet::create('another-user');
    }

    /**
     * @Given /^a vehicle$/
     */
    public function withVehicle(): void
    {
        $this->vehicle = new Vehicle('AZ-123-ER');
    }

    /**
     * @Given /^I have registered this vehicle into my fleet$/
     *
     * @When /^I register this vehicle into my fleet$/
     */
    public function registerVehicle(): void
    {
        $this->getFleet()->registerVehicle($this->getVehicle());
    }

    /**
     * @Given /^this vehicle has been registered into the other user's fleet$/
     */
    public function registerVehicleInAnotherFleet(): void
    {
        $this->getAnotherFleet()->registerVehicle($this->getVehicle());
    }

    /**
     * @When /^I try to register this vehicle into my fleet$/
     */
    public function registerVehicleTwice(): void
    {
        try {
            $this->getFleet()->registerVehicle($this->getVehicle());
        } catch (CantRegisterSameVehicleTwice $cantRegisterSameVehicleTwice) {
            $this->cantRegisterSameVehicleTwice = $cantRegisterSameVehicleTwice;
        }
    }

    /**
     * @Then /^this vehicle should be part of my vehicle fleet$/
     */
    public function assertFleetHasVehicle(): void
    {
        if (!$this->getFleet()->hasVehicle($this->getVehicle())) {
            throw new \RuntimeException(
                'This vehicle should be part of my vehicle fleet'
            );
        }
    }

    /**
     * @Then /^I should be informed this this vehicle has already been registered into my fleet$/
     */
    public function assertCantRegisterSameVehicleTwice(): void
    {
            $this->cantRegisterSameVehicleTwice ?? throw new \RuntimeException(
            'I should be informed this this vehicle has already been registered into my fleet'
        );
    }

    private function getFleet(): Fleet
    {
        return $this->fleet ?? throw new \RuntimeException(
            sprintf('Please, init "%s::fleet" field before using', __CLASS__),
        );
    }

    private function getAnotherFleet(): Fleet
    {
        return $this->anotherFleet ?? throw new \RuntimeException(
            sprintf('Please, init "%s::anotherFleet" field before using', __CLASS__),
        );
    }

    private function getVehicle(): Vehicle
    {
        return $this->vehicle ?? throw new \RuntimeException(
            sprintf('Please, init "%s::vehicle" field before using', __CLASS__),
        );
    }
}