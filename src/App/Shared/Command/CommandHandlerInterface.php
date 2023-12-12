<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\App\Shared\Command;

interface CommandHandlerInterface
{
    public function __invoke(CommandInterface $command): mixed;
}