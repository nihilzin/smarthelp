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
 * Update from 0.85.3 to 0.85.5
 *
 * @return bool for success (will die for most error)
 **/
function update0853to0855()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $updateresult     = true;
    $ADDTODISPLAYPREF = [];

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '0.85.5'));
    $migration->setVersion('0.85.5');

    $backup_tables = false;
    $newtables     = [];

    foreach ($newtables as $new_table) {
       // rename new tables if exists ?
        if ($DB->tableExists($new_table)) {
            $migration->dropTable("backup_$new_table");
            $migration->displayWarning("$new_table table already exists. " .
                                    "A backup have been done to backup_$new_table.");
            $backup_tables = true;
            $query         = $migration->renameTable("$new_table", "backup_$new_table");
        }
    }
    if ($backup_tables) {
        $migration->displayWarning(
            "You can delete backup tables if you have no need of them.",
            true
        );
    }

    $migration->addField("glpi_entities", 'inquest_duration', "integer", ['value' => 0]);

    $migration->addKey('glpi_users', 'begin_date', 'begin_date');
    $migration->addKey('glpi_users', 'end_date', 'end_date');

    $migration->addKey('glpi_knowbaseitems', 'begin_date', 'begin_date');
    $migration->addKey('glpi_knowbaseitems', 'end_date', 'end_date');

   // must always be at the end
    $migration->executeMigration();

    return $updateresult;
}
