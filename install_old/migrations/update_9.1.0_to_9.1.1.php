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
 * Update from 9.1 to 9.1.1
 *
 * @return bool for success (will die for most error)
 **/
function update910to911()
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
    $migration->displayTitle(sprintf(__('Update to %s'), '9.1.1'));
    $migration->setVersion('9.1.1');

    $backup_tables = false;
   // table already exist but deleted during the migration
   // not table created during the migration
   // not table created during the migration
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

   // rectify missing right in 9.1 update
    if (countElementsInTable("glpi_profilerights", ['name' => 'license']) == 0) {
        foreach ($DB->request("glpi_profilerights", ["name" => 'software']) as $profrights) {
            $DB->insertOrDie(
                "glpi_profilerights",
                [
                    'id'           => null,
                    'profiles_id'  => $profrights['profiles_id'],
                    'name'         => "license",
                    'rights'       => $profrights['rights']
                ],
                "9.1 add right for softwarelicense"
            );
        }
    }

   //put you migration script here

   // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
