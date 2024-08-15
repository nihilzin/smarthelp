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
 * Update from 10.0.4 to 10.0.5
 *
 * @return bool for success (will die for most error)
 **/
function update1004to1005()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $updateresult       = true;
    $ADDTODISPLAYPREF   = [];
    $DELFROMDISPLAYPREF = [];
    $update_dir = __DIR__ . '/update_10.0.4_to_10.0.5/';

    //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '10.0.5'));
    $migration->setVersion('10.0.5');

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

    return $updateresult;
}
