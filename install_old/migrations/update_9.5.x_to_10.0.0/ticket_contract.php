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

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if (!$DB->tableExists('glpi_tickets_contracts')) {
    $query = "CREATE TABLE `glpi_tickets_contracts` (
      `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
      `tickets_id` int {$default_key_sign} NOT NULL DEFAULT '0',
      `contracts_id` int {$default_key_sign} NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      UNIQUE KEY `unicity` (`tickets_id`,`contracts_id`),
      KEY `contracts_id` (`contracts_id`)
   ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation};";
    $DB->doQueryOrDie($query, "add table glpi_tickets_contracts");
}

if (!$DB->fieldExists("glpi_entities", "contracts_id_default")) {
    $migration->addField(
        "glpi_entities",
        "contracts_id_default",
        "int {$default_key_sign} NOT NULL DEFAULT 0",
        [
            'after'     => "anonymize_support_agents",
            'value'     => -2,               // Inherit as default value
            'update'    => '0',              // Not enabled for root entity
            'condition' => 'WHERE `id` = 0'
        ]
    );

    $migration->addKey("glpi_entities", "contracts_id_default");
}
