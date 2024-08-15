<?php

/**
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

/**
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

// CleanSoftwareCron cron task
CronTask::register(
    'CleanSoftwareCron',
    'cleansoftware',
    MONTH_TIMESTAMP,
    [
        'state'         => 0,
        'param'         => 1000,
        'mode'          => 2,
        'allowmode'     => 3,
        'logs_lifetime' => 300,
    ]
);
// /CleanSoftwareCron cron task

// Add architecture to software versions
if (!$DB->fieldExists('glpi_softwareversions', 'arch', false)) {
    $migration->addField(
        'glpi_softwareversions',
        'arch',
        'string',
        [
            'after' => 'name'
        ]
    );
    $migration->addKey('glpi_softwareversions', 'arch');
}
// /Add architecture to software versions
