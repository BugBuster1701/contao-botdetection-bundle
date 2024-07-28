<?php

declare(strict_types=1);

error_reporting(E_ALL);

require __DIR__ . 'vendor/autoload.php';

if (!file_exists('tests/cache/referrerblocked.txt')) 
{
    @unlink('tests/cache/desbma.txt');
    @unlink('tests/cache/flameeyes.txt');
    @unlink('tests/cache/stevie_ray.txt');
    @rmdir('tests/cache');
}


$include = function ($file) {
    return file_exists($file) ? include $file : false;
};

// Autoload the contao classes
$fixtureLoader = function ($class): void {
    if (class_exists($class, false) || interface_exists($class, false) || trait_exists($class, false)) {
        return;
    }
    if (false !== strpos($class, '\\') && 0 !== strncmp($class, 'Contao\\', 7)) {
        return;
    }
    if (0 === strncmp($class, 'Contao\\', 7)) {
        $class = substr($class, 7);
    }
    $namespaced = 'Contao\\'.$class;
    if (!class_exists($namespaced) && !interface_exists($namespaced) && !trait_exists($namespaced)) {
        return;
    }
    if (!class_exists($class, false) && !interface_exists($class, false) && !trait_exists($class, false)) {
        class_alias($namespaced, $class);
    }
};
spl_autoload_register($fixtureLoader, true, true);

