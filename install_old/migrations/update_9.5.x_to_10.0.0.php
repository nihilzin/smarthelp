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
 * Update from 9.5.x to 10.0.0
 *
 * @return bool for success (will die for most error)
 **/
function update95xto1000()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $updateresult       = true;
    $ADDTODISPLAYPREF   = [];
    $DELFROMDISPLAYPREF = [];
    $update_dir = __DIR__ . '/update_9.5.x_to_10.0.0/';

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '10.0.0'));
    $migration->setVersion('10.0.0');

    $update_scripts = scandir($update_dir);
    foreach ($update_scripts as $update_script) {
        if (preg_match('/\.php$/', $update_script) !== 1) {
            continue;
        }
        require $update_dir . $update_script;
    }

   // ************ Keep it at the end **************
    foreach ($ADDTODISPLAYPREF as $type => $tab) {
        $rank = 1;
        foreach ($tab as $newval) {
            $DB->updateOrInsert(
                "glpi_displaypreferences",
                [
                    'rank'      => $rank++
                ],
                Toolbox::addslashes_deep(
                    [
                        'users_id'  => "0",
                        'itemtype'  => $type,
                        'num'       => $newval,
                    ]
                )
            );
        }
    }
    foreach ($DELFROMDISPLAYPREF as $type => $tab) {
        $DB->deleteOrDie(
            'glpi_displaypreferences',
            Toolbox::addslashes_deep(
                [
                    'itemtype'  => $type,
                    'num'       => $tab
                ]
            )
        );
    }

    $migration->executeMigration();

    $migration->displayWarning(
        '"utf8mb4" support requires additional migration which can be performed via the "php bin/console migration:utf8mb4" command.'
    );

    return $updateresult;
}
