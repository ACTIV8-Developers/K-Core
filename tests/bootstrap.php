<?php
use Core\Core\Core;
use Core\Core\Controller;
use Core\Util\Util;
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
| Set path to directory where views are stored.
|--------------------------------------------------------------------------
*/
Controller::$viewPath = __DIR__. '/MockApp/MockViews/';
/*
|--------------------------------------------------------------------------
| Set name of the public directory.
|--------------------------------------------------------------------------
*/
Util::$publicPath = 'public';
/*
|--------------------------------------------------------------------------
| Register timezone
|--------------------------------------------------------------------------
*/
date_default_timezone_set('Europe/Belgrade');
/*
|--------------------------------------------------------------------------
| Boot mockup app
|--------------------------------------------------------------------------
*/
Core::getInstance(__DIR__.'/MockApp')
					->setControllerNamespace('')
					->boot();
/*
|--------------------------------------------------------------------------
| Mockup request
|--------------------------------------------------------------------------
*/
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/test';