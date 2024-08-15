<?php

/*!
 * ---------------------------------------------------------------------
 *
 * Powered by Urich Souza 
 *
 * https://github.com/nihilzin
 *
 * @copyright 2023 Urich Souza and contributors.
 * 
 * ---------------------------------------------------------------------
 */

/** @var array $CFG_GLPI */
global $CFG_GLPI;

// Ensure current directory when run from crontab
chdir(__DIR__);

$SECURITY_STRATEGY = 'no_check'; // in GLPI mode, cronjob can also be triggered from public pages

include('../inc/includes.php');

if (!is_writable(GLPI_LOCK_DIR)) {
   //TRANS: %s is a directory
    echo "\t" . sprintf(__('ERROR: %s is not writable') . "\n", GLPI_LOCK_DIR);
    echo "\t" . __('run script as apache user') . "\n";
    exit(1);
}

if (!isCommandLine()) {
   //The advantage of using background-image is that cron is called in a separate
   //request and thus does not slow down output of the main page as it would if called
   //from there.
    $image = pack("H*", "47494638396118001800800000ffffff00000021f90401000000002c0000000" .
                       "018001800000216848fa9cbed0fa39cb4da8bb3debcfb0f86e248965301003b");
    header("Content-Type: image/gif");
    header("Content-Length: " . strlen($image));
    header("Cache-Control: no-cache,no-store");
    header("Pragma: no-cache");
    header("Connection: close");
    echo $image;
    flush();

    CronTask::launch(CronTask::MODE_INTERNAL);
} else if (isset($_SERVER['argc']) && ($_SERVER['argc'] > 1)) {
   // Parse command line options

    $mode = CronTask::MODE_EXTERNAL; // when taskname given, will allow --force
    for ($i = 1; $i < $_SERVER['argc']; $i++) {
        if ($_SERVER['argv'][$i] == '--force') {
            $mode = -CronTask::MODE_EXTERNAL;
        } else if (is_numeric($_SERVER['argv'][$i])) {
           // Number of tasks
            CronTask::launch(CronTask::MODE_EXTERNAL, intval($_SERVER['argv'][$i]));
           // Only check first parameter when numeric is passed
            break;
        } else {
           // Single Task name
            CronTask::launch($mode, 1, $_SERVER['argv'][$i]);
        }
    }
} else {
   // Default from configuration
    CronTask::launch(CronTask::MODE_EXTERNAL, $CFG_GLPI['cron_limit']);
}
