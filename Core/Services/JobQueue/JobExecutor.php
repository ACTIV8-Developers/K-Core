<?php

namespace App\Services\JobQueue;

use Core\Container\ContainerAware;
use Exception;
use Redis;

const JOB_KEY = 'JOBS';

/**
 * @property Redis cache
 */
class JobExecutor extends ContainerAware
{
    public function __construct(Redis $cache)
    {
        $this->cache = $cache;
    }

    public function execute()
    {
        $log = "Reading job queue...\n";
        $log .= "Total of " . $this->cache->lSize(JOB_KEY) . " pending jobs\n";
        while ($job = $this->cache->rPop(JOB_KEY)) {
            $job = unserialize($job);
            $log .= "Executing job " . $job->getName() . "\n";
            if ($job) {
                $job->setContainer($this->getContainer());
                $result = false;
                try {
                    $result = $job->execute();
                } catch (Exception $e) {
                    $log .= "Exception: " . $e->getMessage() .".\n";
                }
                if (!$result) {
                    $log .= "Failed job:".$job->getName()."...\n";
                }
                $log .= "Done.\n";
            } else {
                $log .= "Failed un serialize job.\n";
            }
        }

        $log .= "Queue done exiting.\n";
        return $log;
    }

    public function queue(Job $job)
    {
        $this->cache->lPush(JOB_KEY, serialize($job));
    }

    public function status()
    {
        $log = "Total of " . $this->cache->lSize(JOB_KEY) . " pending jobs\n";
        return $log;
    }
}