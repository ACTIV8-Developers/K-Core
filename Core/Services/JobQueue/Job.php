<?php

namespace Core\Services\JobQueue;


use Core\Services\FileUpload\FileUpload;
use Core\Services\Mailer\Mailer;
use Core\Services\Zipper\Zipper;
use Core\Container\ContainerAware;
use Core\Database\Interfaces\DatabaseInterface;
use Core\Http\Request;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Job
 * @property array user
 * @property array permissions
 * @property Request request
 * @property Mailer mailer
 * @property CacheInterface cache
 * @property DatabaseInterface db
 * @property FileUpload fileupload
 * @property Zipper zipper
 * @package App\Controllers
 */
abstract class Job extends ContainerAware
{
    abstract function getName();

    abstract function execute();
}