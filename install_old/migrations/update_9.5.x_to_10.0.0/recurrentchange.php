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
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

$migration->displayMessage("Adding recurrent changes");

$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

$DB->updateOrDie(
    'glpi_crontasks',
    [
        'itemtype' => 'CommonITILRecurrentCron',
        'name'     => 'RecurrentItems'
    ],
    [
        'itemtype' => 'TicketRecurrent',
        'name'     => 'ticketrecurrent',
    ],
    "CommonITILReccurent crontask"
);

$recurrent_change_table = 'glpi_recurrentchanges';
if (!$DB->tableExists($recurrent_change_table)) {
    $DB->doQueryOrDie("CREATE TABLE `$recurrent_change_table` (
         `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
         `name` varchar(255) DEFAULT NULL,
         `comment` text,
         `entities_id` int {$default_key_sign} NOT NULL DEFAULT '0',
         `is_recursive` tinyint NOT NULL DEFAULT '0',
         `is_active` tinyint NOT NULL DEFAULT '0',
         `changetemplates_id` int {$default_key_sign} NOT NULL DEFAULT '0',
         `begin_date` timestamp NULL DEFAULT NULL,
         `periodicity` varchar(255) DEFAULT NULL,
         `create_before` int NOT NULL DEFAULT '0',
         `next_creation_date` timestamp NULL DEFAULT NULL,
         `calendars_id` int {$default_key_sign} NOT NULL DEFAULT '0',
         `end_date` timestamp NULL DEFAULT NULL,
         PRIMARY KEY (`id`),
         KEY `entities_id` (`entities_id`),
         KEY `is_recursive` (`is_recursive`),
         KEY `is_active` (`is_active`),
         KEY `changetemplates_id` (`changetemplates_id`),
         KEY `next_creation_date` (`next_creation_date`)
      ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation};");
}

$migration->addRight('recurrentchange', ALLSTANDARDRIGHT, [
    'change' => UPDATE,
    'ticketrecurrent' => UPDATE,
]);
