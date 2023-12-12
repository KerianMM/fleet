<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\App\Shared\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): mixed;
}