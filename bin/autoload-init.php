<?php
$dir = null;
$internal = __DIR__ . '/../vendor/';
if (realpath($internal)) {
    $dir = $internal;
}
/** @var Composer\Autoload\ClassLoader $autoloader */
$autoloader = require $dir . 'autoload.php';
$autoloader->addPsr4('AndKirby\\MageDbHelper\\', array(realpath(__DIR__ . '/../src/MageDbHelper/')));
