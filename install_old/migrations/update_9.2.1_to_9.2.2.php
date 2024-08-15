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

/** @file
 * @brief
 */

/**
 * Update from 9.2.1 to 9.2.2
 *
 * @return bool for success (will die for most error)
 **/
function update921to922()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $current_config   = Config::getConfigurationValues('core');
    $updateresult     = true;
    $ADDTODISPLAYPREF = [];

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '9.2.2'));
    $migration->setVersion('9.2.2');

    $migration->addConfig([
        'smtp_retry_time' => 5,
    ]);

    $migration->addPostQuery(
        $DB->buildDelete("glpi_configs", [
            'context'   => "core",
            'name'      => "default_graphtype"
        ])
    );

    $migration->addPostQuery(
        $DB->buildDelete(
            "glpi_crontasks",
            ['name' => "optimize"]
        )
    );

   // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
