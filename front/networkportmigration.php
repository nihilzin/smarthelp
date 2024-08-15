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

/** @var \DBMysql $DB */
global $DB;

include('../inc/includes.php');

Session::checkRight("networking", UPDATE);

if (!$DB->tableExists('glpi_networkportmigrations')) {
    Html::displayNotFoundError();
}

Html::header(
    NetworkPortMigration::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "tools",
    "migration",
    "networkportmigration"
);

Search::show('NetworkPortMigration');

Html::footer();
