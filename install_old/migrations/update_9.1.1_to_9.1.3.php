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
 * Update from 9.1.1 to 9.1.3
 *
 * @return bool for success (will die for most error)
 **/
function update911to913()
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
    $migration->displayTitle(sprintf(__('Update to %s'), '9.1.3'));
    $migration->setVersion('9.1.3');

    $backup_tables = false;
   // table already exist but deleted during the migration
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

   //Fix duplicated search options
    if (countElementsInTable("glpi_displaypreferences", ['itemtype' => 'IPNetwork', 'num' => '17']) == 0) {
        $DB->updateOrDie(
            "glpi_displaypreferences",
            [
                "num" => 17
            ],
            [
                'itemtype'  => "IPNetwork",
                'num'       => 13
            ],
            "9.1.3 Fix duplicate IPNetwork Gateway search option"
        );
    }
    if (countElementsInTable("glpi_displaypreferences", ['itemtype' => 'IPNetwork', 'num' => '18']) == 0) {
        $DB->updateOrDie(
            "glpi_displaypreferences",
            [
                "num" => 18
            ],
            [
                'itemtype'  => "IPNetwork",
                'num'       => 14
            ],
            "9.1.3 Fix duplicate IPNetwork addressable network search option"
        );
    }

    $migration->addField(
        "glpi_softwarelicenses",
        "contact",
        "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL"
    );
    $migration->addField(
        "glpi_softwarelicenses",
        "contact_num",
        "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL"
    );

   // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
