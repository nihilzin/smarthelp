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
 * Update from 9.4.5 to 9.4.6
 *
 * @return bool for success (will die for most error)
 **/
function update945to946()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;
    $updateresult     = true;
   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '9.4.6'));
    $migration->setVersion('9.4.6');
    $DB->deleteOrDie(
        'glpi_profilerights',
        [
            'name'  => 'backup'
        ]
    );
   // ************ Keep it at the end **************
    $migration->executeMigration();
    return $updateresult;
}
