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
 * Update from 0.80.1 to 0.80.3
 *
 * @return bool for success (will die for most error)
 **/
function update0801to0803()
{
    /**
     * @var \Migration $migration
     */
    global $migration;

    $updateresult     = true;
    $ADDTODISPLAYPREF = [];

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '0.80.3'));
    $migration->setVersion('0.80.3');

    $migration->changeField("glpi_fieldunicities", 'fields', 'fields', "text");

    $migration->dropKey('glpi_ocslinks', 'unicity');
    $migration->migrationOneTable('glpi_ocslinks');
    $migration->addKey(
        "glpi_ocslinks",
        ['ocsid', 'ocsservers_id'],
        "unicity",
        "UNIQUE"
    );

   // must always be at the end
    $migration->executeMigration();

    return $updateresult;
}
