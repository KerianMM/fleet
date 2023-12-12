#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Kerianmm\Fleet\Infra\Cli\FleetCli;

(new FleetCli())->run();
