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
 * Update from 9.4.6 to 9.4.7
 *
 * @return bool for success (will die for most error)
 **/
function update946to947()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $updateresult     = true;

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '9.4.7'));
    $migration->setVersion('9.4.7');

    $DB->updateOrDie('glpi_events', ['type'   => 'dcrooms'], ['type' => 'serverroms']);

   // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
