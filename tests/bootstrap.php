<?php
/*
|--------------------------------------------------------------------------
| Set application path.
|--------------------------------------------------------------------------
*/
define('APP', __DIR__.'/MockApp');
/*
|--------------------------------------------------------------------------
| Set name of directory and namespace where controllers are stored.
|--------------------------------------------------------------------------
| This needs to match structure defined in composer.json file, usually
| controllers are stored in App/Controllers.
*/
define('CONTROLERS', '');
/*
|--------------------------------------------------------------------------
| Set name of directory and namespace where models are stored.
|--------------------------------------------------------------------------
| This needs to match structure defined in composer.json file.
*/
define('MODELS', 'Models');
/*
|--------------------------------------------------------------------------
| Set path to directory where views are stored.
|--------------------------------------------------------------------------
*/
define('APPVIEW', APP.'/MockViews/');
/*
|--------------------------------------------------------------------------
| Set path to file containing routes.
|--------------------------------------------------------------------------
*/
define('ROUTES', APP.'/routes.php');
/*
|--------------------------------------------------------------------------
| Set name of the public directory.
|--------------------------------------------------------------------------
*/
define('PUBLIC_DIR', 'public');
/*
|--------------------------------------------------------------------------
| Set path to the public directory.
|--------------------------------------------------------------------------
*/
define('PUBLIC_PATH', __DIR__.'/../'.PUBLIC_DIR.'/');
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
Core\Util\AliasLoader::getInstance(require(APP.'/Config/Aliases.php'))->register();
/*
|--------------------------------------------------------------------------
| Mockup request
|--------------------------------------------------------------------------
*/
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/test';