<?php
use Core\Util\AliasLoader;

ob_start();
/*
|--------------------------------------------------------------------------
| Register the composer auto loader
|--------------------------------------------------------------------------
*/
require __DIR__.'/../vendor/autoload.php';
/*
|--------------------------------------------------------------------------
| Register aliases auto loader.
|--------------------------------------------------------------------------
| Additional auto loader for prettier class names.
*/
AliasLoader::getInstance(require(__DIR__.'/MockApp/Config/Aliases.php'))->register();
/*
|--------------------------------------------------------------------------
| Register timezone
|--------------------------------------------------------------------------
*/
date_default_timezone_set('Europe/Belgrade');
/*
|--------------------------------------------------------------------------
| Mockup request
|--------------------------------------------------------------------------
*/
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/test';