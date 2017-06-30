<?php

error_reporting(E_ALL);

require 'tests/vendor/autoload.php';

if (!file_exists('tests/cache/referrerblocked.txt')) 
{
    @unlink('tests/cache/desbma.txt');
    @unlink('tests/cache/flameeyes.txt');
    @unlink('tests/cache/stevie_ray.txt');
    @rmdir('tests/cache');
}
