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

/** @file
 * @brief
 */

/**
 * Update from 9.3.0 to 9.3.1
 *
 * @return bool for success (will die for most error)
 **/
function update930to931()
{
    /**
     * @var \Migration $migration
     */
    global $migration;

    $current_config   = Config::getConfigurationValues('core');
    $updateresult     = true;
    $ADDTODISPLAYPREF = [];

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '9.3.1'));
    $migration->setVersion('9.3.1');

    /** Change field type */
    $migration->changeField(
        'glpi_notifications_notificationtemplates',
        'notifications_id',
        'notifications_id',
        'integer'
    );
    /** /Change field type */

   // add option to hide/show source on login page
    $migration->addConfig(['display_login_source' => 1]);

   // supplier now have use_notification = 1 by default
    $migration->changeField(
        'glpi_suppliers_tickets',
        'use_notification',
        'use_notification',
        'bool',
        [
            'value' => 1
        ]
    );

   // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
