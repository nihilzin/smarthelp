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
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

// Add pending reason table
if (!$DB->tableExists('glpi_pendingreasons')) {
    $query = "CREATE TABLE `glpi_pendingreasons` (
         `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
         `entities_id` int {$default_key_sign} NOT NULL DEFAULT '0',
         `is_recursive` tinyint NOT NULL DEFAULT '0',
         `name` varchar(255) DEFAULT NULL,
         `followup_frequency` int NOT NULL DEFAULT '0',
         `followups_before_resolution` int NOT NULL DEFAULT '0',
         `itilfollowuptemplates_id` int {$default_key_sign} NOT NULL DEFAULT '0',
         `solutiontemplates_id` int {$default_key_sign} NOT NULL DEFAULT '0',
         `comment` text,
         PRIMARY KEY (`id`),
         KEY `name` (`name`),
         KEY `itilfollowuptemplates_id` (`itilfollowuptemplates_id`),
         KEY `entities_id` (`entities_id`),
         KEY `is_recursive` (`is_recursive`),
         KEY `solutiontemplates_id` (`solutiontemplates_id`)
      ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = $default_charset COLLATE = $default_collation;";
    $DB->doQueryOrDie($query, "10.0 add table glpi_pendingreasons");
}

// Add pending reason items table
if (!$DB->tableExists('glpi_pendingreasons_items')) {
    $query = "CREATE TABLE `glpi_pendingreasons_items` (
         `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
         `pendingreasons_id` int {$default_key_sign} NOT NULL DEFAULT '0',
         `items_id` int {$default_key_sign} NOT NULL DEFAULT '0',
         `itemtype` varchar(100) NOT NULL DEFAULT '',
         `followup_frequency` int NOT NULL DEFAULT '0',
         `followups_before_resolution` int NOT NULL DEFAULT '0',
         `bump_count` int NOT NULL DEFAULT '0',
         `last_bump_date` timestamp NULL DEFAULT NULL,
         PRIMARY KEY (`id`),
         UNIQUE KEY `unicity` (`items_id`,`itemtype`),
         KEY `pendingreasons_id` (`pendingreasons_id`),
         KEY `item` (`itemtype`,`items_id`)
      ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = $default_charset COLLATE = $default_collation;";
    $DB->doQueryOrDie($query, "10.0 add table glpi_pendingreasons_items");
}

// Add pendingreason right
$migration->addRight('pendingreason', ALLSTANDARDRIGHT, ['itiltemplate' => UPDATE]);

// Add user for automatic bump and followup
$config = Config::getConfigurationValues('core');
if (empty($config['system_user'])) {
    $user = new User();

    $system_user_name = 'glpi-system';
    if ($user->getFromDBbyName($system_user_name)) {
        $system_user_name .= '-' . Toolbox::getRandomString(8);
    }
    $system_user_params = [
        'name'          => $system_user_name,
        'realname'      => 'Support',
        'password'      => '',
        'authtype'      => 1,
    ];
    $DB->insertOrDie('glpi_users', $system_user_params, "Can't add 'glpi-system' user");

    $migration->addConfig(['system_user' => $DB->insertId()], 'core');
}

// Add crontask for auto bump and auto solve
$crontask = new CronTask();
if (empty($crontask->find(['itemtype' => 'PendingReasonCron']))) {
    $cron_added = CronTask::register(
        'PendingReasonCron',
        'pendingreason_autobump_autosolve',
        30 * MINUTE_TIMESTAMP,
        [
            'state'         => 1,
            'mode'          => 2,
            'allowmode'     => 3,
            'logs_lifetime' => 60,
        ]
    );

    if (!$cron_added) {
        die("Can't add PendingReasonCron");
    }
}

// Name change, might be needed for a few user who used the feature before release
if ($DB->fieldExists('glpi_pendingreasons_items', 'auto_bump')) {
    $migration->changeField('glpi_pendingreasons_items', 'auto_bump', 'followup_frequency', 'int');
}

if ($DB->fieldExists('glpi_pendingreasons_items', 'auto_solve')) {
    $migration->changeField('glpi_pendingreasons_items', 'auto_solve', 'followups_before_resolution', 'int');
}

if ($DB->fieldExists('glpi_pendingreasons', 'auto_bump')) {
    $migration->changeField('glpi_pendingreasons', 'auto_bump', 'followup_frequency', 'int');
}

if ($DB->fieldExists('glpi_pendingreasons', 'auto_solve')) {
    $migration->changeField('glpi_pendingreasons', 'auto_solve', 'followups_before_resolution', 'int');
}
