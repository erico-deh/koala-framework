<?php
class Kwf_Util_Maintenance_Dispatcher
{
    public static function getAllMaintenanceJobs()
    {
        static $ret;
        if (isset($ret)) return $ret;

        foreach (Kwc_Abstract::getComponentClasses() as $c) {
            if (is_instance_of($c, 'Kwf_Util_Maintenance_JobProviderInterface')) {
                $providerClasses[] = $c;
            }
        }
        foreach (Kwf_Model_Abstract::findAllInstances() as $model) {
            if ($model instanceof Kwf_Util_Maintenance_JobProviderInterface) {
                $providerClasses[] = get_class($model);;
            }
        }

        $jobClasses = array();
        foreach ($providerClasses as $c) {
            $jobClasses = array_merge($jobClasses, call_user_func(array($c, 'getMaintenanceJobs')));
        }
        $jobClasses = array_unique($jobClasses);
        $ret = array();
        foreach ($jobClasses as $i) {
            $ret[] = new $i();
        }
        usort($ret, array('Kwf_Util_Maintenance_Dispatcher', '_compareJobsPriority'));
        return $ret;
    }

    public static function _compareJobsPriority($a, $b)
    {
        $a = $a->getPriority();
        $b = $b->getPriority();
        if ($a == $b) return 0;
        return ($a < $b) ? -1 : 1;
    }

    public static function executeJobs($jobFrequency, $debug)
    {
        foreach (self::getAllMaintenanceJobs() as $job) {
            if ($job->getFrequency() == $jobFrequency) {
                if ($debug) echo "executing ".get_class($job)."\n";
                $t = microtime(true);
                if ($jobFrequency == Kwf_Util_Maintenance_Job_Abstract::FREQUENCY_DAILY) {
                    $cmd = "php bootstrap.php maintenance-jobs run-job --job=".escapeshellarg(get_class($job));
                    if ($debug) $cmd .= " --debug";
                    $retVar = null;
                    passthru($cmd, $retVar);
                    if ($retVar) {
                        $e = new Kwf_Exception("Maintenance job ".get_class($job)." failed with exit code $retVar");
                        $e->logOrThrow();
                    }
                } else {
                    try {
                        $job->execute($debug);
                    } catch (Exception $e) {
                        file_put_contents('php://stderr', $e->toString()."\n");
                        if (!$e instanceof Kwf_Exception_Abstract) $e = new Kwf_Exception_Other($e);
                        $e->logOrThrow();
                    }
                }
                $t = microtime(true)-$t;
                if ($debug) echo "executed ".get_class($job)." in ".round($t, 3)."s\n";
                $maxTime = 60;
                if ($jobFrequency == Kwf_Util_Maintenance_Job_Abstract::FREQUENCY_DAILY) {
                    $maxTime = 60*60;
                }
                if ($t > $maxTime) {
                    $msg = "Maintenance job ".get_class($job)." took ".round($t, 3)."s to execute which is above the limit of $maxTime.";
                    file_put_contents('php://stderr', $msg."\n");
                    $e = new Kwf_Exception($msg);
                    $e->logOrThrow();
                }
                if ($debug) echo "\n";
            }
        }
    }
}
