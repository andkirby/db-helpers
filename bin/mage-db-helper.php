<?php
require_once 'autoload-init.php';

use AndKirby\MageDbHelper\Application;
use AndKirby\MageDbHelper\Command\Install;

$app = new Application();
$app->add(new Install(
    `cd ~ && pwd`,
    realpath(__DIR__ . '/../src')
));
$app->run();
