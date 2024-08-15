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
 * Update from 9.5.3 to 9.5.4
 *
 * @return bool for success (will die for most error)
 **/
function update953to954()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $updateresult = true;

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '9.5.4'));
    $migration->setVersion('9.5.4');

   /* Remove invalid Profile SO */
    $DB->deleteOrDie('glpi_displaypreferences', ['itemtype' => 'Profile', 'num' => 62]);
   /* /Remove invalid Profile SO */

   /* Add is_default_profile */
    $migration->addField("glpi_profiles_users", "is_default_profile", "bool");
   /* /Add is_default_profile */

   // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
