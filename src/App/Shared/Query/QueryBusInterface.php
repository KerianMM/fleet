<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\App\Shared\Query;

interface QueryBusInterface
{
    public function dispatch(QueryInterface $command): mixed;
}