<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Infra\Cli;

use Kerianmm\Fleet\App\Command\CreateFleetCommand;
use Kerianmm\Fleet\App\Command\ParkCommand;
use Kerianmm\Fleet\App\Command\RegisterVehicleCommand;
use Kerianmm\Fleet\App\Query\LocationQuery;
use Kerianmm\Fleet\App\Shared\Command\CommandBusInterface;
use Kerianmm\Fleet\App\Shared\Query\QueryBusInterface;
use Kerianmm\Fleet\Domain\Exception\CantLocalizeVehicleToTheSameLocation;
use Kerianmm\Fleet\Domain\Exception\CantRegisterSameVehicleTwice;
use Kerianmm\Fleet\Domain\Model\Fleet;
use Kerianmm\Fleet\Domain\Model\Location;
use Kerianmm\Fleet\Infra\Shared\Container\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * TODO: validate answers before dispatch datas
 * TODO: error handling
 */
final class FleetCli extends SingleCommandApplication
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;

    public function __construct()
    {
        parent::__construct('Manage fleets');

        $container = Container::boot();
        /** @phpstan-ignore-next-line */
        $this->commandBus = $container->get(CommandBusInterface::class);
        /** @phpstan-ignore-next-line */
        $this->queryBus = $container->get(QueryBusInterface::class);

        $this->setCode(function (InputInterface $input, OutputInterface $output): int {
            /** @var QuestionHelper $questionHelper */
            $questionHelper = $this->getHelper('question');

            return $this->menu($questionHelper, $input, $output);
        });
    }

    private function createFleet(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output): int {
        /** @var string $userId */
        $userId = $questionHelper->ask($input, $output, new Question('Fleet user id :'));

        /** @var Fleet $fleet */
        $fleet = $this->commandBus->dispatch(new CreateFleetCommand($userId));

        (new SymfonyStyle($input, $output))->success(sprintf('New fleet "%s"', $fleet->id));

        return $this->menu($questionHelper, $input, $output);
    }

    private function registerVehicle(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output): int {
        /** @var string $id */
        $id          = $questionHelper->ask($input, $output, new Question('Fleet id :'));
        /** @var string $plateNumber */
        $plateNumber = $questionHelper->ask($input, $output, new Question('Vehicle plate number :'));

        $io = new SymfonyStyle($input, $output);

        try {
            $this->commandBus->dispatch(new RegisterVehicleCommand($id, $plateNumber));
            $io->success(sprintf('Vehicle "%s" registered in fleet "%s"', $plateNumber, $id));
        } catch (CantRegisterSameVehicleTwice) {
            $io->warning(sprintf('Vehicle "%s" already registered in fleet "%s"', $plateNumber, $id));
        }

        return $this->menu($questionHelper, $input, $output);
    }

    private function parkVehicle(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output): int {
        /** @var string $fleetId */
        $fleetId     = $questionHelper->ask($input, $output, new Question('Fleet id :'));
        /** @var string $plateNumber */
        $plateNumber = $questionHelper->ask($input, $output, new Question('Vehicle plateNumber :'));
        /** @var string $latitude */
        $latitude    = $questionHelper->ask($input, $output, new Question('Latitude :'));
        $latitude    = (float) $latitude;
        /** @var string $longitude */
        $longitude   = $questionHelper->ask($input, $output, new Question('Longitude :'));
        $longitude   = (float) $longitude;

        $io = new SymfonyStyle($input, $output);

        try {
            $this->commandBus->dispatch(new ParkCommand($fleetId, $plateNumber, $latitude, $longitude));
            $io->success(sprintf('Vehicle "%s" localized at "%s" "%s"', $plateNumber, $latitude, $longitude));
        } catch (CantLocalizeVehicleToTheSameLocation) {
            $io->warning(sprintf('Vehicle "%s" already localized at "%s" "%s"', $plateNumber, $latitude, $longitude));
        }

        return $this->menu($questionHelper, $input, $output);
    }

    private function askVehicleLocalization(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output): int {
        /** @var string $fleetId */
        $fleetId     = $questionHelper->ask($input, $output, new Question('Fleet id :'));
        /** @var string $plateNumber */
        $plateNumber = $questionHelper->ask($input, $output, new Question('Vehicle plateNumber :'));

        $io = new SymfonyStyle($input, $output);

        $location = $this->queryBus->dispatch(new LocationQuery($fleetId, $plateNumber));
        if ($location instanceof Location) {
            $io->success(sprintf('Vehicle "%s" localized at "%s" "%s"', $plateNumber, $location->latitude, $location->longitude));
        } else {
            $io->warning(sprintf('Vehicle "%s" is not localized', $plateNumber));
        }

        return $this->menu($questionHelper, $input, $output);
    }

    private function menu(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output): int {
        $answer = $questionHelper->ask($input, $output, new ChoiceQuestion('Select an action :', [
            'create fleet',
            'register vehicle',
            'park vehicle',
            'ask vehicle localization',
            'exit',
        ]));

        return match ($answer) {
            'register vehicle'          => $this->registerVehicle($questionHelper, $input, $output),
            'park vehicle'              => $this->parkVehicle($questionHelper, $input, $output),
            'create fleet'              => $this->createFleet($questionHelper, $input, $output),
            'ask vehicle localization'  => $this->askVehicleLocalization($questionHelper, $input, $output),
            default                     => Command::SUCCESS,
        };
    }
}