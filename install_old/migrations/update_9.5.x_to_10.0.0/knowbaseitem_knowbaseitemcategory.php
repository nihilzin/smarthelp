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

$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

/* Update link KB_item-category from 1-1 to 1-n */
if (!$DB->tableExists('glpi_knowbaseitems_knowbaseitemcategories')) {
    $query = "CREATE TABLE `glpi_knowbaseitems_knowbaseitemcategories` (
      `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
      `knowbaseitems_id` int {$default_key_sign} NOT NULL DEFAULT '0',
      `knowbaseitemcategories_id` int {$default_key_sign} NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      KEY `knowbaseitems_id` (`knowbaseitems_id`),
      KEY `knowbaseitemcategories_id` (`knowbaseitemcategories_id`)
      ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation};";
    $DB->doQueryOrDie($query, "add table glpi_knowbaseitems_knowbaseitemcategories");
}

if ($DB->fieldExists('glpi_knowbaseitems', 'knowbaseitemcategories_id')) {
    $iterator = $DB->request([
        'SELECT' => ['id', 'knowbaseitemcategories_id'],
        'FROM'   => 'glpi_knowbaseitems',
        'WHERE'  => ['knowbaseitemcategories_id' => ['>', 0]]
    ]);
    if (count($iterator)) {
       //migrate existing data
        foreach ($iterator as $row) {
            $DB->insertOrDie("glpi_knowbaseitems_knowbaseitemcategories", [
                'knowbaseitemcategories_id'   => $row['knowbaseitemcategories_id'],
                'knowbaseitems_id'            => $row['id']
            ]);
        }
    }
    $migration->dropField('glpi_knowbaseitems', 'knowbaseitemcategories_id');
}
