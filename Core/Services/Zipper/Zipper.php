<?php

namespace Core\Services\Zipper;

use Exception;
use Phar;
use PharData;

class Zipper
{
    /**
     * @param $tarLoc
     * @param $list
     * @return bool
     */
    public function tar($tarLoc, $list)
    {
        // Backup app files first in tar archive
        try {
            // Make archive
            $tar = new PharData($tarLoc . '.tar');
            if (is_array($list)) {
                foreach ($list as $key => $l) {
                    $tar->addFile(realpath($l), basename($l));
                }
            } else {
                $tar->buildFromDirectory($list);
            }
        } catch (Exception $e) {
            error_log(print_r($e->getMessage(), 1));
            return false;
        }

        // Compress tar to gz
        try {
            $tar->compress(Phar::GZ);
            unlink($tarLoc . '.tar');

        } catch (Exception $e) {
            error_log(print_r($e->getMessage(), 1));
            return false;
        }

        return true;
    }

    /**
     * @param $zipLoc
     * @param $list
     * @return bool
     */
    public function zip($zipLoc, $list)
    {
        // Backup app files first in tar archive
        try {
            // Make archive
            $zip = new PharData($zipLoc . '.zip', null, null, Phar::ZIP);
            if (is_array($list)) {
                foreach ($list as $key => $l) {
                    $zip->addFile(realpath($l), basename($l));
                }
            } else {
                $zip->buildFromDirectory($list);
            }
        } catch (Exception $e) {
            error_log(print_r($e->getMessage(), 1));
            return false;
        }

        return true;
    }
}