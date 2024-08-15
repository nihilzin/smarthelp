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
 * Update from 10.0.5 to 10.0.6
 *
 * @return bool for success (will die for most error)
 **/
function update1005to1006()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $updateresult       = true;
    $ADDTODISPLAYPREF   = [];
    $DELFROMDISPLAYPREF = [];
    $update_dir = __DIR__ . '/update_10.0.5_to_10.0.6/';

    //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '10.0.6'));
    $migration->setVersion('10.0.6');

    $update_scripts = scandir($update_dir);
    foreach ($update_scripts as $update_script) {
        if (preg_match('/\.php$/', $update_script) !== 1) {
            continue;
        }
        require $update_dir . $update_script;
    }

    // ************ Keep it at the end **************
    $migration->updateDisplayPrefs($ADDTODISPLAYPREF, $DELFROMDISPLAYPREF);

    $migration->executeMigration();

    return $updateresult;
}
