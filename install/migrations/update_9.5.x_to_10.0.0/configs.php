<?php

/*!
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
 * @var \Migration $migration
 */

$migration->displayMessage('Add new configurations / user preferences');
$migration->addConfig([
    'default_central_tab'   => 0,
    'page_layout'           => 'vertical',
    'fold_menu'             => 0,
    'fold_search'           => 0,
    'savedsearches_pinned'  => 0,
    'richtext_layout'       => 'classic',
    'user_restored_ldap'    => 0,
    'timeline_order'        => 'natural',
    'itil_layout'           => 0,
    'from_email'            => '',
    'from_email_name'       => '',
    'noreply_email'         => '',
    'noreply_email_name'    => '',
    'replyto_email'         => '',
    'replyto_email_name'    => '',
    'support_legacy_data'   => 1, // GLPI instances updated from GLPI < 10.0 should support legacy data
]);
$migration->addField("glpi_users", "default_central_tab", "tinyint DEFAULT 0");
$migration->addField('glpi_users', 'page_layout', 'char(20) DEFAULT NULL', ['after' => 'palette']);
$migration->addField('glpi_users', 'fold_menu', 'tinyint DEFAULT NULL', ['after' => 'page_layout']);
$migration->addField('glpi_users', 'fold_search', 'tinyint DEFAULT NULL', ['after' => 'fold_menu']);
$migration->addField('glpi_users', 'savedsearches_pinned', 'text', ['after' => 'fold_search', 'nodefault' => true]);
$migration->addField('glpi_users', 'richtext_layout', 'char(20) DEFAULT NULL', ['after' => 'savedsearches_pinned']);
$migration->addField("glpi_users", "timeline_order", "char(20) DEFAULT NULL", ['after' => 'savedsearches_pinned']);
$migration->addField('glpi_users', 'itil_layout', 'text', ['after' => 'timeline_order']);

$migration->displayMessage('Drop old configurations / user preferences');
$migration->dropField('glpi_users', 'layout');
Config::deleteConfigurationValues('core', ['layout']);
Config::deleteConfigurationValues('core', ['use_ajax_autocompletion']);
Config::deleteConfigurationValues('core', ['transfers_id_auto']);
